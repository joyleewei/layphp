<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class IndexController extends AdminbaseController {
    private $user_model;
    public function __construct(){
        parent::__construct();
        $this->user_model = D('Admin/user');
        $this->nav_model = D('Admin/AdminNav');
    }
    public function index(){
        $user_id = $_SESSION['user_info']['id'];
        $map['id'] = $user_id;
        $user_info = $this->user_model->where($map)->find();
        $this->assign('user_info',$user_info);
        $this->display();
    }

    public function main(){
        $this->display();
    }

    public function nav(){
        $nav_data=$this->nav_model->getTreeData('level','listorder,id');
        $list = array();
        $arr = array();
        foreach($nav_data as $k=>$v){
            if(!empty($v['_data'])){
                $list['href'] = '';
                $list['href'] = $v['mca'].'.html';
                $list['title'] = $v['name'];
                $list['icon'] = $v['icon'];
                $list['spread'] = false;
                $list['children'] = array();
                foreach($v['_data'] as $key=>$value){
                    $child['title'] = $value['name'];
                    $child['icon'] = $value['icon'];
                    $child['href'] = $value['mca'].'.html';
                    $child['spread'] = 'false';
                    array_push($list['children'],$child);
                }
                array_push($arr,$list);
                unset($list);
            }else{
                $list['href'] = $v['mca'].'.html';
                $list['title'] = $v['name'];
                $list['icon'] = $v['icon'];
                $list['spread'] = false;
                array_push($arr,$list);
                unset($list);
            }
        }
        echo json_encode($arr,true);
    }

}