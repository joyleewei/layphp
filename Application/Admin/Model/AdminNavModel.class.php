<?php
namespace Admin\Model;
use Think\Model;
class AdminNavModel extends Model{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间) 1:MODEL_INSERT  2:MODEL_UPDATE 3:MODEL_BOTH
        array('name', 'require', '菜单名称不能为空！', 1, 'regex',3),
        array('module', 'require', '模块名不能为空！', 1, 'regex',3),
        array('controller', 'require', '控制器名称不能为空！', 1, 'regex',3),
        array('action', 'require', '方法名称不能为空！', 1, 'regex',3),
        array('module,controller,action', 'checkAction', '同样的记录已经存在！', 1, 'callback',1),
        array('id,module,controller,action', 'checkActionUpdate', '同样的记录已经存在！', 1, 'callback',2),
    );

    //验证action是否重复添加---[没整明白语法]
    public function checkAction($data){
        //检查是否重复添加
        $map['mca'] = '/'.ucfirst(trim($data['module'])).'/'.ucfirst(trim($data['controller'])).'/'.lcfirst(trim($data['action']));
        $find = $this->where($map)->find();
        if($find){
            return false;
        }
        return true;
    }

    //验证action是否重复添加---[没整明白语法]
    public function checkActionUpdate($data) {
        //检查是否重复添加
        $id=$data['id'];
        unset($data['id']);
        $map['mca'] = '/'.ucfirst(trim($data['module'])).'/'.ucfirst(trim($data['controller'])).'/'.lcfirst(trim($data['action']));
        $find = $this->field('id')->where($map)->find();
        if (isset($find['id']) && $find['id']!=$id) {
            return false;
        }
        return true;
    }

    /**
     * 获取全部菜单
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array       	结构数据
     */
    public function getTreeData($type='tree',$order='',$map=''){
        // 判断是否需要排序
        $map_data = array();
        if(!empty($map)){
            $map_data = $map;
        }
        if(empty($order)){
            $data=$this->where($map_data)->select();
        }else{
            $data=$this->where($map_data)->order('listorder is null,id is null,'.$order)->select();
        }
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,'name','id','pid');
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;','id');
            if($_SESSION['user_info']['id']!=1){
                // 显示有权限的菜单
                $auth=new \Think\Auth();
                foreach ($data as $k => $v) {
                    if ($auth->check($v['mca'],$_SESSION['user_info']['id'])) {
                        foreach ($v['_data'] as $m => $n) {
                            if(!$auth->check($n['mca'],$_SESSION['user_info']['id'])){
                                unset($data[$k]['_data'][$m]);
                            }
                        }
                    }else{
                        // 删除无权限的菜单
                        unset($data[$k]);
                    }
                }
            }
        }
        return $data;
    }
}