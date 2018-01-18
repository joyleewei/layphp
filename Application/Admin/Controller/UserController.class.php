<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class UserController extends AdminbaseController{
    protected $user_model;
    public function __construct(){
        parent::__construct();
        $this->user_model = D('Admin/user');
        $this->area_model = M('Area');
        $this->auth_group_model = D('Admin/AuthGroup');
        $this->auth_group_access_model = D('Admin/AuthGroupAccess');
    }
    // 个人中心和修改信息。
    public function info(){
        if(IS_POST){
            $data = $this->user_model->create();
            if(!empty($data)){
                $data['update_time'] = time();
                $data['birth'] = strtotime($data['birth']);
                $save = $this->user_model->save($data);
                if(!empty($save)){
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，修改成功',
                        'nickname'=>$data['nickname'],
                        'image'=>$data['image']
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，修改失败，请稍后重试'
                    );
                }
            }else{
                $error = $this->user_model->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $user_id  = I('get.id',0,'intval');
            if(empty($user_id)){
                $user_id = $_SESSION['user_info']['id'];
            }
            $map['id'] = $user_id;
            $map['state'] = 1;
            $user_info = $this->user_model->where($map)->find();
            // 获取用户的所属组id
            $gid = $this->auth_group_access_model->get_gid($user_id);
            // 网站用户组信息
            $map_group['id'] = array('in',$gid);
            $group_info = $this->auth_group_model->where($map_group)->getField('id,title');
            try{
                $redis = new \Redis();
                $res = $redis->connect('127.0.0.1',6379);
                if(empty($res)){
                    throw new \Exception('连接失败');
                }
            }catch(\Exception $e){
                $msg = $e->getMessage();
                $this->error($msg,U('admin/index/index'));
                exit();
            }
            $province = $redis->hGetAll('province');;
            if(empty($province)){
                $map_province['level'] = 1;
                $province = $this->area_model->where($map_province)->getField('id,areaname');
                $redis->hMset('province',$province);
            }

            if(!empty($user_info['province'])){
                $city = $redis->hGetAll('city:'.$user_info['province']);
                if(empty($city)){
                    $map_city['parentid'] = $user_info['province'];
                    $map_city['level'] = 2;
                    $city = $this->area_model->where($map_city)->getField('id,areaname');
                    $redis->hMset('city:'.$user_info['province'],$city);
                }
                $this->assign('city',$city);
            }

            if(!empty($user_info['city'])){
                $area = $redis->hGetAll('area:'.$user_info['city']);
                if(empty($area)){
                    $map_area['parentid'] = $user_info['city'];
                    $map_area['level'] = 3;
                    $area = $this->area_model->where($map_area)->getField('id,areaname');
                    $redis->hMset('area:'.$user_info['city'],$area);
                }
                $this->assign('area',$area);
            }

            $this->assign('user_info',$user_info);
            $this->assign('group_info',$group_info);
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function changepwd(){
        if(IS_POST){
            $data = I('post.','','addslashes,htmlspecialchars');
            if($data['password']!==$data['confirm_password']){
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，两次密码输入不一致，请确认后重试'
                );
            }else{
                if($data['id'] !=$_SESSION['user_info']['id']){
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，操作错误，请确认后重试。'
                    );
                }else{
                    $map['id'] = $data['id'];
                    $map['state'] = 1;
                    $user_info = $this->user_model->where($map)->find();
                    if($user_info['password']!==md5($data['old_password'])){
                        $res = array(
                            'status'=>0,
                            'msg'=>'您好，旧密码错误，请确认后重试。'
                        );
                    }else{
                        $map['id'] = $data['id'];
                        $data_user['password'] = md5($data['password']);
                        $res = $this->user_model->where($map)->save($data_user);
                        if(!empty($res)){
                            $res = array(
                                'status'=>1,
                                'msg'=>'您好，修改成功'
                            );
                        }else{
                            $res = array(
                                'status'=>0,
                                'msg'=>'修改失败，请稍后重试'
                            );
                        }
                    }
                }
            }
            echo json_encode($res,true);
            exit();
        }else{
            $user_id  = I('get.id',0,'intval');
            if(empty($user_id)){
                $user_id = $_SESSION['user_info']['id'];
            }
            $map['id'] = $user_id;
            $map['state'] = 1;
            $user_info = $this->user_model->where($map)->find();
            if(!empty($user_info)){
                $this->assign('user_info',$user_info);
                $this->display();
            }else{
                $this->error('您好，不存在');
            }
        }
    }

}