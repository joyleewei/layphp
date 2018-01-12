<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class UserController extends AdminbaseController{
    public function info(){
        $user_info = $_SESSION['user_info'];
        $this->assign('user_info',$user_info);
        $this->display();
    }
}
