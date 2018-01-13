<?php
namespace Api\Controller;
use Think\Controller;
use Think\Exception;

class CityController extends Controller{
    public function get(){
        if(IS_POST){
            $post = I('post.',0,'intval,addslashes,htmlspecialchars');
            $id = $post['areaid'];
            try{
                $redis = new \Redis();
                $conn_res = $redis->connect('127.0.0.1',6379);
                if(empty($conn_res)){
                    throw new Exception('连接失败');
                }
            }catch(Exception $e){
                echo $e->getMessage();
                $res = array(
                    'staus'=>0,
                    'msg'=>'接口出现错误，请稍后重试'
                );
                echo json_encode($res,true);
                exit();
            }

            if($redis->hExists('province',$id)){
                // id 为省id
                $city = $redis->hGetAll('city:'.$id);
                if(empty($city)){
                    $map_city['parentid'] = $id;
                    $map_city['level'] = 2;
                    $city = M('area')->where($map_city)->getField('id,areaname');
                    $redis->hMset('city:'.$id,$city);
                }
                $res = array(
                    'status'=>1,
                    'count'=>count($city),
                    'area'=>$city
                );
            }else{
                // id 为市id
                $area = $redis->hGetAll('area:'.$id);
                if(empty($area)){
                    $map_area['parentid'] = $id;
                    $map_area['level'] = 3;
                    $area = M('area')->where($map_area)->getField('id,areaname');
                    $redis->hMset('area:'.$id,$area);
                }
                $res = array(
                    'status'=>1,
                    'count'=>count($area),
                    'area'=>$area
                );
            }
        }else{
            $res = array(
                'staus'=>0,
                'msg'=>'请求方法错误，请确认后重试'
            );
        }
        echo json_encode($res,true);
        exit();
    }

    public function info(){
        try{
            $redis = new \Redis();
            $res = $redis->connect('127.0.0.1',6379);
            if(empty($res)){
                throw new Exception('连接失败');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
        /*
        //设置province
        $province = M('Area')->where('`level` = 1')->getField('id,areaname');
        pr($province);
        $redis->hMset('province',$province);
        */

        $province = M('Area')->where('`level` = 1')->order('sort asc')->getField('id,areaname');
        $redis->hMset('province',$province);

        foreach($province as $k=>$v){
            $map_city['level'] = 2;
            $map_city['parentid'] = $k;
            $city = M('Area')->where($map_city)->getField('id,areaname');
            $redis->hMset('city:'.$k,$city);
            foreach($city as $key=>$value){
                $map_area['level'] = 3;
                $map_area['parentid'] = $key;
                $area = M('Area')->where($map_area)->getField('id,areaname');
                $res = $redis->hMset('area:'.$key,$area);
                $result[$key] = $res;
            }
        }
        //pr($redis->hGet('city:130000'));
    }

}