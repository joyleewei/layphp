<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AuthController extends AdminbaseController{
    private $user_model;
    private $auth_rule_model;
    private $auth_group_model;
    private $auth_group_access_model;
    public function __construct(){
        parent::__construct();
        $this->user_model = D('Admin/User');
        $this->area_model = M('Area');
        $this->auth_rule_model = D('Admin/AuthRule');
        $this->auth_group_model = D('Admin/AuthGroup');
        $this->auth_group_access_model = D('Admin/AuthGroupAccess');
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
                    $this->auth_group_access_model->where($map_access)->delete();
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
    // 分配权限
    public function group_assign(){
        if(IS_POST){
            $id = I('post.id',0,'intval,htmlspecialchars,addslashes');
            if(!empty($id)){
                $map_group['id'] = $id;
                $count = $this->auth_group_model->where($map_group)->count();
                //file_put_contents('post.log',$this->auth_group_model->getLastSql()."\r\n\r\n",FILE_APPEND);
                if($count > 0){
                    $rules = I('post.rules','htmlspecialchar,addslashes');
                    $save_res = $this->auth_group_model->where($map_group)->setField('rules',$rules);
                    if(!empty($save_res)){
                        $res = array(
                            'status' => 1,
                            'msg'=>'您好，修改权限成功。'
                        );
                    }else{
                        $res = array(
                            'status' => 0,
                            'msg'=>'您好，修改权限失败，请稍后重试。'
                        );
                    }
                }else{
                    $res = array(
                        'status' => 0,
                        'msg'=>'您好，该角色组不存在，请确认后重试。'
                    );
                }
            }else{
                $res = array(
                    'status' => 0,
                    'msg'=>'您好，请选择需要修改的角色组。'
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $id = I('get.id',0,'intval');
            if(!empty($id)){
                $map_group['id'] = $id;
                $group_info = $this->auth_group_model->where($map_group)->find();
                if(!empty($group_info)){
                    $group_rule = explode(',',$group_info['rules']);
                    $rule_list = $this->auth_rule_model->getTreeData('level','id','title','id','pid');
                    $this->assign('group_info',$group_info);
                    $this->assign('group_rule',$group_rule);
                    $this->assign('rule_list',$rule_list);
                    $this->display();
                }else{
                    $this->error('您好，该用户组不存在,请确认后重试',U('/admin/auth/group'));
                }
            }else{
                $this->error('您好，该用户组不存在',U('/admin/auth/group'));
            }
        }
    }

    // 管理员列表
    public function user(){
        // state: 1:启用 2:禁用 3:删除
        $map['state'] = array('in',array(1,2));
        $map['id'] = array('neq',1);
        $list = $this->user_model->where($map)->getField('id,username,nickname,sex,state,phone,email');
        $this->assign('list',$list);
        $this->display();
    }
    // 增加管理员
    public function user_add(){
        if(IS_POST){
            $data = $this->user_model->create();
            if(!empty($data)){
                $data['password'] = md5($data['password']);
                $data['myself'] = strip_tags($data['myself']);
                $data['create_time'] = time();
                $data['birth'] = strtotime($data['birth']);
                $add_res = $this->user_model->add($data);
                if(!empty($add_res)){
                    // 加入用户组
                    $rules = I('post.rules');
                    foreach($rules as $k=>$v){
                        $data_group['uid'] =$add_res;
                        $data_group['group_id'] = $v;
                        $this->auth_group_access_model->add($data_group);
                        unset($data_group);
                    }
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，添加成功'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，添加失败，请稍后重试'
                    );
                }
            }else{
                $error = $this->user_model->getError();
                $res = array(
                    'status' => 0,
                    'msg'=>$error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            // 获取省 市 县
            try{
                $redis = new \Redis();
                $res = $redis->connect('127.0.0.1',6379);
                if(empty($res)){
                    throw new \Exception('连接失败');
                }
            }catch(\Exception $e){
                $msg = $e->getMessage();
                $this->error($msg,U('admin/index/index'));
                exit();
            }
            $province = $redis->hGetAll('province');;
            if(empty($province)){
                $map_province['level'] = 1;
                $province = $this->area_model->where($map_province)->getField('id,areaname');
                $redis->hMset('province',$province);
            }
            // 获取用户组
            $group = $this->auth_group_model->get_group();
            $this->assign('group',$group);
            $this->assign('province',$province);
            $this->display();
        }
    }

    // 编辑管理员
    public function user_edit(){
        if(IS_POST){
            $data = $this->user_model->create();
            if(!empty($data)){
                $data['update_time'] = time();
                $data['birth'] = strtotime($data['birth']);
                $data['myself'] = strip_tags($data['myself']);
                $save_res = $this->user_model->save($data);
                if(!empty($save_res)){
                    $rules = I('post.rules');
                    $map_group['uid'] = $data['id'];
                    $this->auth_group_access_model->where($map_group)->delete();
                    // 加入用户组
                    $rules = I('post.rules');
                    foreach($rules as $k=>$v){
                        $data_group['uid'] =$data['id'];
                        $data_group['group_id'] = $v;
                        $this->auth_group_access_model->add($data_group);
                        unset($data_group);
                    }

                    if($data['id'] == $_SESSION['user_info']['id']){
                        $res = array(
                            'status'=>1,
                            'msg'=>'您好，添加成功',
                            'nickname'=>$data['nickname'],
                            'image'=>$data['image']
                        );
                    }else{
                        $res = array(
                            'status'=>1,
                            'msg'=>'您好，添加成功'
                        );
                    }
                }else{
                    $res = array(
                        'status' => 0,
                        'msg' => '您好，编辑失败，请稍后重试'
                    );
                }
            }else{
                $error = $this->user_model->getError();
                $res = array(
                    'status' => 0,
                    'msg' => $error
                );
            }
            echo json_encode($res,true);
            exit();
        }else{
            $id = I('get.id',0,'intval');
            if(!empty($id)){
                $user_info = $this->user_model->get_info($id);
                if(!empty($user_info)){
                    // 获取用户组
                    $group = $this->auth_group_model->get_group();
                    // 获取用户所在的组
                    $user_group = $this->auth_group_access_model->get_gid($id);
                    try{
                        $redis = new \Redis();
                        $res = $redis->connect('127.0.0.1',6379);
                        if(empty($res)){
                            throw new \Exception('连接失败');
                        }
                    }catch(\Exception $e){
                        $msg = $e->getMessage();
                        $this->error($msg,U('admin/index/index'));
                        exit();
                    }
                    $province = $redis->hGetAll('province');;
                    if(empty($province)){
                        $map_province['level'] = 1;
                        $province = $this->area_model->where($map_province)->getField('id,areaname');
                        $redis->hMset('province',$province);
                    }
                    if(!empty($user_info['province'])){
                        $city = $redis->hGetAll('city:'.$user_info['province']);
                        if(empty($city)){
                            $map_city['parentid'] = $user_info['province'];
                            $map_city['level'] = 2;
                            $city = $this->area_model->where($map_city)->getField('id,areaname');
                            $redis->hMset('city:'.$user_info['province'],$city);
                        }
                        $this->assign('city',$city);
                    }

                    if(!empty($user_info['city'])){
                        $area = $redis->hGetAll('area:'.$user_info['city']);
                        if(empty($area)){
                            $map_area['parentid'] = $user_info['city'];
                            $map_area['level'] = 3;
                            $area = $this->area_model->where($map_area)->getField('id,areaname');
                            $redis->hMset('area:'.$user_info['city'],$area);
                        }
                        $this->assign('area',$area);
                    }
                    $this->assign('group',$group);
                    $this->assign('user_group',$user_group);
                    $this->assign('user_info',$user_info);
                    $this->assign('province',$province);
                    $this->assign('city',$city);
                    $this->assign('area',$area);
                    $this->display();
                }else{
                    $this->error('您好，该管理员信息不存在，请确认后重试',U('/admin/auth/user'));
                }
            }else{
                $this->error('您好，该管理员信息不存在，请确认后重试',U('/admin/auth/user'));
            }
        }
    }

    // 删除管理员
    public function user_delete(){
        if(IS_POST){
            $id = I('post.id');
            if(empty($id)){
                $res = array(
                    'status'=>0,
                    'msg'=>'您好，请选择需要删除的管理员。'
                );
            }else{
                if(is_array($id)){
                    $ids = explode(',',$id['id']);
                    $map['id'] = array('in',$ids);
                    $map_access['uid'] = array('in',$ids);
                }else{
                    $map['id'] = $id;
                    $map_access['uid'] = $id;
                }
                $save_res = $this->user_model->where($map)->setField('state',3);
                if(!empty($save_res)){
                    $this->auth_group_access_model->where($map_access)->delete();
                    file_put_contents('sql.log',$this->auth_group_access_model->getLastSql()."\r\n\r\n",FILE_APPEND);
                    $res = array(
                        'status'=>1,
                        'msg'=>'您好，删除后台管理员成功'
                    );
                }else{
                    $res = array(
                        'status'=>0,
                        'msg'=>'您好，操作失败，请稍后重试'
                    );
                }
            }
            echo json_encode($res,true);
            exit();
        }else{
            $this->error('您好，操作失败，请确认后重试',U('/admin/auth/user'));
        }
    }

    // 查看管理员信息
    public function user_view(){
        $user_id  = I('get.id',0,'intval');
        if(empty($user_id)){
            $user_id = $_SESSION['user_info']['id'];
        }
        $map['id'] = $user_id;
        $map['state'] = array('in',array(1,2));
        $user_info = $this->user_model->where($map)->find();
        if(!empty($user_info)){
            // 获取用户的所属组id
            $gid = $this->auth_group_access_model->get_gid($user_id);
            // 网站用户组信息
            $map_group['id'] = array('in',$gid);
            $group_info = $this->auth_group_model->where($map_group)->getField('id,title');
            try{
                $redis = new \Redis();
                $res = $redis->connect('127.0.0.1',6379);
                if(empty($res)){
                    throw new \Exception('连接失败');
                }
            }catch(\Exception $e){
                $msg = $e->getMessage();
                $this->error($msg,U('admin/index/index'));
                exit();
            }
            if(!empty($user_info['province'])){
                $province = $redis->hGet('province',$user_info['province']);
                if(!empty($user_info['city'])){
                    $city = $redis->hGet('city:'.$user_info['province'],$user_info['city']);
                    if(!empty($user_info['area'])){
                        $area = $redis->hGet('area:'.$user_info['city'],$user_info['area']);
                    }else{
                        $area = '未知';
                    }
                }else{
                    $city = '未知';
                }
            }else{
                $province = '未知';
            }
            $this->assign('user_info',$user_info);
            $this->assign('group_info',$group_info);
            $this->assign('province',$province);
            $this->assign('city',$city);
            $this->assign('area',$area);
            $this->display();
        }else{
            $this->error('您好，该用户不存在',U('/admin/auth/user'));
        }
    }
}