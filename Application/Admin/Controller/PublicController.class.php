<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller{
    public function login(){
        if(IS_AJAX){
            $data = I('post.','','addslashes,htmlspecialchars');
            $map_user['username'] = $data['username'];
            $map_user['password'] = md5($data['password']);
            $map_user['state'] = 1;
            $user_info = M('User')->where($map_user)->find();

            if(!empty($user_info)){
                unset($user_info['password']);
                session('user_info',$user_info);
                $res = array(
                    'status'=>1,
                    'msg'=>'您好，登陆成功',
                );
            }else{
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，登陆失败，请确认后重试'
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            if(!empty($_SESSOIN['user_info'])){
                redirect('/admin/index/index');
            }else{
                $this->display();
            }
        }
    }
}