<?php
namespace Common\Controller;
use Think\Controller;
class AdminbaseController extends Controller{
    public function __construct(){
        parent::__construct();
        if(empty($_SESSION['user_info'])){
            if(IS_AJAX){
                $this->error("您还没有登录！",U("/admin/public/login"));
            }else{
                header("Location:".U("/admin/public/login"));
                exit();
            }
        }else{
            if($_SESSION['user_info']['id'] != 1){
                $auth=new \Think\Auth();
                $rule_name='/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
                $result=$auth->check($rule_name,$_SESSION['user_info']['id']);
                if(!$result){
                    $this->error('您没有权限访问',U('/admin/index/index'));
                }
            }
        }
    }
}