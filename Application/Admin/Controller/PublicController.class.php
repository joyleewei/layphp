<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller{
    // 登录
    public function login(){
        if(IS_AJAX){
            $data = I('post.','','addslashes,htmlspecialchars');
            $verify = new \Think\Verify();
            $code_res = $verify->check($data['code']);
            if(empty($code_res)){
                $res = array(
                    'status'=>2,
                    'msg'=>'您好，验证码错误，请重试'
                );
            }else{
                $map_user['username'] = $data['username'];
                $map_user['password'] = md5($data['password']);
                $map_user['state'] = 1;
                $user_info = M('User')->where($map_user)->find();
                // $user_info = M('User')->where($map_user)->getField('id,username,nickname,image,sex');
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
                        'msg'=>'您好，账号或密码错误，请确认后重试'
                    );
                }
            }
            echo json_encode($res,true);
            exit();
        }else{
            if(!empty($_SESSION['user_info'])){
                redirect('/admin/index/index');
            }else{
                $this->display();
            }
        }
    }

    // 退出
    public function logout(){
        session('user_info',null);
        redirect('/admin/public/login.html');
    }
}