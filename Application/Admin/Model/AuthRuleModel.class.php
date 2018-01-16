<?php
namespace Admin\Model;
use Think\Model;
class AuthRuleModel extends Model{
    //自动验证
    protected $_validate = array(
        array('title', 'require', '规则名称不能为空！', 1, 'regex',3),
        array('module', 'require', '模块名不能为空！', 1, 'regex',3),
        array('controller', 'require', '控制器名称不能为空！', 1, 'regex',3),
        array('action', 'require', '方法名称不能为空！', 1, 'regex',3),
        array('module,controller,action', 'checkAction', '同样的记录已经存在！', 1, 'callback',1),
        array('id,module,controller,action', 'checkActionUpdate', '同样的记录已经存在！', 1, 'callback',2),
    );

    //验证action是否重复添加
    public function checkAction($data){
        //检查是否重复添加
        $map['name'] = '/'.ucfirst(trim($data['module'])).'/'.ucfirst(trim($data['controller'])).'/'.lcfirst(trim($data['action']));
        $find = $this->where($map)->find();
        if(!empty($find)){
            return false;
        }else{
            return true;
        }
    }

    //验证action是否重复添加
    public function checkActionUpdate($data){
        //检查是否重复添加
        $id=$data['id'];
        unset($data['id']);
        $map['name'] = '/'.ucfirst(trim($data['module'])).'/'.ucfirst(trim($data['controller'])).'/'.lcfirst(trim($data['action']));
        $find = $this->field('id')->where($map)->find();
        if(isset($find['id']) && $find['id']!=$id){
            return false;
        }
        return true;
    }

    /**
     * 获取全部菜单
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array       	结构数据
     */
    public function getTreeData($type='tree',$order='',$name='name',$child='id',$parent='pid'){
        // 判断是否需要排序
        if(empty($order)){
            $data=$this->select();
        }else{
            $data=$this->order($order.' is null,'.$order)->select();
        }
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }

}