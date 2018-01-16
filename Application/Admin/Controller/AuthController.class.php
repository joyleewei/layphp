<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AuthController extends AdminbaseController{
    private $auth_rule_model;
    private $auth_group_model;
    private $auth_groupaccess_model;
    public function __construct(){
        parent::__construct();
        $this->auth_rule_model = D('Admin/AuthRule');
        $this->auth_group_model = D('Admin/AuthGroup');
        $this->auth_groupaccess_model = D('Admin/AuthGroupAccess');
    }
    // 权限管理列表
    public function index(){
        $list = $this->auth_rule_model->getTreeData('tree','id','title','id','pid');

        $this->assign('list',$list);
        $this->display();
    }
    // 增加权限
    public function auth_add(){
        if(IS_POST){
            $data = $this->auth_rule_model->create();
            if(!empty($data)){
                $data['module'] = ucfirst(trim($data['module']));
                $data['controller'] = ucfirst(trim($data['controller']));
                $data['action'] = lcfirst(trim($data['action']));
                $data['name'] = '/'.$data['module'].'/'.$data['controller'].'/'.$data['action'];
                $res = $this->auth_rule_model->add($data);
                if(!empty($res)){
                    $res = array(
                        'status' =>1,
                        'msg'=>'您好，添加成功'
                    );
                }else{
                    $res = array(
                        'status' =>0,
                        'msg'=>'您好，添加失败，请稍后重试'
                    );
                }
            }else{
                $error = $this->auth_rule_model->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $pid = I('get.pid',0,'intval');
            $rule_cate = $this->auth_rule_model->getTreeData('tree','id','title','id','pid');
            $this->assign('rule_cate',$rule_cate);
            $this->assign('pid',$pid);
            $this->display();
        }
    }
    // 编辑权限
    public function auth_edit(){
        if(IS_POST){
            $data = $this->auth_rule_model->create();
            if(!empty($data)){
                $data['module'] = ucfirst(trim($data['module']));
                $data['controller'] = ucfirst(trim($data['controller']));
                $data['action'] = lcfirst(trim($data['action']));
                $data['name'] = '/'.$data['module'].'/'.$data['controller'].'/'.$data['action'];
                $save_res = $this->auth_rule_model->save($data);
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
                $error = $this->auth_rule_model->getError();
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
                $rule_info = $this->auth_rule_model->where($map)->find();
                if(!empty($rule_info)){
                    $map_rule['pid'] =0;
                    $rule_cate = $this->auth_rule_model->getTreeData('tree','id','title','id','pid');
                    $this->assign('rule_cate',$rule_cate);
                    $this->assign('rule_info',$rule_info);
                    $this->display();
                }else{
                    $this->error('您好，该权限菜单不存在，请确认后重试',U('admin/auth/index'));
                }
            }else{
                $this->error('您好，该权限菜单不存在，请确认后重试!!!',U('admin/auth/index'));
            }
        }
    }
    // 删除权限管理
    public function auth_delete(){
        if(IS_POST){
            $id = I('post.id',0,'intval,htmlspecialchars,addslashes');
            if(!empty($id)){
                $map_count['pid'] = $id;
                $count = $this->auth_rule_model->where($map_count)->count();
                if($count>0){
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，该权限菜单下有子菜单，请先删除子菜单。'
                    );
                }else{
                    $map_del['id'] = $id;
                    $del_res = $this->auth_rule_model->where($map_del)->delete();
                    if(!empty($del_res)){
                        $res = array(
                            'status'=>1,
                            'msg'=>'您好，删除权限菜单成功。'
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
                    'msg'=>'您好，该权限菜单不存在，请确认后重试'
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $this->error('您好，接口错误，请确认后重试!!!',U('/admin/index/index'));
        }
    }

    // 用户组管理
    public function group(){
        $list = $this->auth_group_model->order('id asc')->getField('id,title,status,remark');

        $this->assign('list',$list);
        $this->display();
    }

    // 增加用户组
    public function group_add(){
        if(IS_POST){
            $data = $this->auth_group_model->create();
            if(!empty($data)){
                $add_res = $this->auth_group_model->add($data);
                if(!empty($add_res)){
                    $res = array(
                        'status' =>1,
                        'msg'=>'您好，添加成功。'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，添加失败，请稍后重试。'
                    );
                }
            }else{
                $error = $this->auth_group_model->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $this->display();
        }
    }

    // 编辑用户组
    public function group_edit(){
        if(IS_POST){
            $data = $this->auth_group_model->create();
            if(!empty($data)){
                $save_res = $this->auth_group_model->save($data);
                if(!empty($save_res)){
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，编辑成功。'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，编辑错误，请稍后重试'
                    );
                }
            }else{
                $error = $this->auth_group_model->getError();
                $res = array(
                    'status'=>0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $id = I('get.id',0,'intval');
            if(!empty($id)){
                $map_group['id'] = $id;
                $group_info = $this->auth_group_model->where($map_group)->field('id,title,status,remark')->select();
                if(!empty($group_info)){
                    $this->assign('group_info',$group_info[0]);
                    $this->display();
                }else{
                    $this->error('您好，该用户组不存在，请确认后重试！！！',U('/admin/auth/group'));
                }
            }else{
                $this->error('您好，该用户组不存在，请确认后重试！！！',U('/admin/auth/group'));
            }

        }
    }
    // 删除用户组
    public function group_delete(){
        if(IS_POST){
            $id = I('post.id',0,'intval');
            if(!empty($id)){
                $map_group['id'] = $id;
                $res_group = $this->auth_group_model->where($map_group)->delete();
                if(!empty($res_group)){
                    $map_access['group_id'] = $id;
                    $this->auth_groupaccess_model->where($map_access)->delete();
                    $res = array(
                        'status' => 1,
                        'msg'=>'您好，删除成功。'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，删除失败，请稍后重试。'
                    );
                }
            }else{
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，该用户组不存在，请确认后重试'
                );
            }
        }else{
            $res = array(
                'status'=>0,
                'msg'=>'您好，接口错误，请确认后重试'
            );
        }
        echo json_encode($res,true);
        exit();
    }
    // 用户组的启用/禁用
    public function group_isshow(){
        if(IS_POST){
            $id = I('post.id',0,'intval');
            $status = I('post.status',1,'intval');
            if(!empty($id)){
                $map_group['id'] = $id;
                $count = $this->auth_group_model->where($map_group)->count();
                if($count > 0){
                    $show_res = $this->auth_group_model->where($map_group)->setField('status',$status);
                    if(!empty($show_res)){
                        $res = array(
                            'status'=>1,
                            'msg'=>'您好，修改成功。'
                        );
                    }else{
                        $res = array(
                            'status'=>2,
                            'msg'=>'您好，修改失败，请稍后重试。'
                        );
                    }
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，请用户组不存在，请确认后重试。'
                    );
                }
            }else{
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，请选择需要改变的用户组。'
                );
            }
        }else{
            $res = array(
                'status'=>0,
                'msg'=>'您好，操作失败，请确认后重试'
            );
        }
        echo json_encode($res,true);
        exit();
    }

    // 管理员列表
    public function user(){

        $this->display();
    }
    // 增加管理员
    public function user_add(){

    }
    // 编辑管理员
    public function user_edit(){

    }
    // 删除管理员
    public function user_delete(){

    }


}