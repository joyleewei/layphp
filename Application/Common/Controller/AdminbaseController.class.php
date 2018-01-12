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
        }
    }
}