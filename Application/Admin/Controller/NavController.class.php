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
        $list=D('AdminNav')->getTreeData('tree','listorder,id','');

        $this->assign('list',$list);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $data =  $this->nav_model->create();
            if(!empty($data)){
                $data['module'] = ucfirst(trim($data['module']));
                $data['controller'] = ucfirst(trim($data['controller']));
                $data['action'] = lcfirst(trim($data['action']));
                $data['mca'] = '/'.$data['module'].'/'.$data['controller'].'/'.$data['action'];
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
            $pid = I('get.pid',0,'intval,addslashes,htmlspecialchars');
            // 第一级菜单
            $map_nav['type'] = 1;
            $map_nav['pid'] =0;
            $first_nav = $this->nav_model->where($map_nav)->getField('id,name');
            $this->assign('first_nav',$first_nav);
            $this->assign('pid',$pid);
            $this->display();
        }
    }

    public function edit(){
        if(IS_POST){
            $data = $this->nav_model->create();
            if(!empty($data)){
                $data['module'] = ucfirst(trim($data['module']));
                $data['controller'] = ucfirst(trim($data['controller']));
                $data['action'] = lcfirst(trim($data['action']));
                $data['mca'] = '/'.$data['module'].'/'.$data['controller'].'/'.$data['action'];
                $save_res = $this->nav_model->save($data);
                if(!empty($save_res)){
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，修改成功！'
                    );
                }else{
                    $res = array(
                        'status' => 0,
                        'msg' => '您好，修改失败，请稍后重试'
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
            $id = I('get.id',0,'intval,addslashes,htmlspecialchars');
            if(!empty($id)){
                $map['id'] = $id;
                $nav_info = $this->nav_model->where($map)->find();

                if(!empty($nav_info)){
                    // 第一级菜单
                    $map_nav['type'] = 1;
                    $map_nav['pid'] =0;
                    $first_nav = $this->nav_model->where($map_nav)->getField('id,name');
                    $this->assign('first_nav',$first_nav);

                    $this->assign('nav_info',$nav_info);
                    $this->display();
                }else{
                    $this->error('您好，该菜单不存在，请确认后重试',U('admin/nav/index'));
                }
            }else{
                $this->error('您好，该菜单不存在，请确认后重试!!!',U('admin/nav/index'));
            }
        }


    }

    public function delete(){
        if(IS_POST){
            $id = I('post.id',0,'intval,htmlspecialchars,addslashes');
            if(!empty($id)){
                $map_count['pid'] = $id;
                $count = $this->nav_model->where($map_count)->count();
                if($count>0){
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，该菜单下有子菜单，请先删除子菜单。'
                    );
                }else{
                    $map_del['id'] = $id;
                    $del_res = $this->nav_model->where($map_del)->delete();
                    if(!empty($del_res)){
                        $res = array(
                            'status'=>1,
                            'msg'=>'您好，删除菜单成功。'
                        );
                    }else{
                        $res = array(
                            'status'=>0,
                            'msg'=>'您好，接口错误，请稍后重试。'
                        );
                    }
                }
            }else{
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，该菜单不存在，请确认后重试'
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $this->error('您好，接口错误，请确认后重试!!!',U('/admin/index/index'));
        }
    }

    // 更新排序
    public function sort(){
        $data = I('post.');
        foreach($data as $k=>$v){
            $map_nav['id'] = $k;
            $this->nav_model->where($map_nav)->setField('listorder',$v);
        }
        $res = array(
            'status'=>1,
            'msg'=>'修改成功'
        );
        echo json_encode($res);
        exit();
    }
}