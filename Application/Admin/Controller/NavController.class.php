<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class NavController extends AdminbaseController{
    private $nav_model;
    public function __construct(){
        parent::__construct();
        $this->nav_model = D('Admin/AdminNav');
    }

    // 菜单显示[暂时只做二级菜单，不做无限级菜单]
    public function index(){
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $data =  $this->nav_model->create();
            if(!empty($data)){
                $data['module'] = ucfirst(trim($data['module']));
                $data['controller'] = ucfirst(trim($data['controller']));
                $data['action'] = lcfirst(trim($data['controller']));
                $data['mca'] = $data['module'].'/'.$data['controller'].'/'.$data['action'];
                if(!empty($data['icon'])){
                    $data['icon'] = htmlspecialchars_decode(trim($data['icon']));
                }
                $add_res = $this->nav_model->add($data);
                if(!empty($add_res)){
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，添加成功'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，添加失败'
                    );
                }
            }else{
                $error = $this->nav_model->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            // 第一级菜单
            $map_nav['type'] = 1;
            $map_nav['pid'] = 0;
            $first_nav = $this->nav_model->where($map_nav)->getField('id,name');
            $this->assign('first_nav',$first_nav);
            $this->display();
        }
    }
}