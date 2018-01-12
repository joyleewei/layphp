<?php
namespace Api\Controller;
use Think\Controller;
use Think\Upload;
class UploadController extends Controller{
    public function image(){
        if(IS_POST){
            $savepath=date('Ymd').'/';
            //上传处理类
            $config=array(
                'rootPath' => './upload/avator/',
                'savePath' => $savepath,
                'maxSize' => 11048576,
                'saveName'   =>    array('uniqid',''),
                'exts'=>array('jpg','gif','png','jpeg'),
                'autoSub'    =>    false,
            );
            $upload = new Upload($config);
            $info = $upload->upload();
            if(!$info){
                $error = $upload->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }else{
                $path = '/upload/avator/'.$info['file']['savepath'].$info['file']['savename'];
                $res = array(
                    'status'=>1,
                    'path'=>$path,
                    'msg'=>'上传成功'
                );
            }
            echo json_encode($res,true);
            exit();
        }
    }
}