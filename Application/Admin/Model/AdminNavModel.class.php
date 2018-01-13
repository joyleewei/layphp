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
    public function checkAction($data) {
        //检查是否重复添加
        $find = $this->where($data)->find();
        if ($find) {
            return false;
        }
        return true;
    }

    //验证action是否重复添加---[没整明白语法]
    public function checkActionUpdate($data) {
        //检查是否重复添加
        $id=$data['id'];
        unset($data['id']);
        $find = $this->field('id')->where($data)->find();
        if (isset($find['id']) && $find['id']!=$id) {
            return false;
        }
        return true;
    }

}