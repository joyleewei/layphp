<?php
namespace Admin\Model;
use Think\Model;
class AuthGroupModel extends Model{
    //自动验证
    protected $_validate = array(
        array('title', 'require', '用户组名称不能为空！'),

    );

    public function get_group(){
        $map['status'] = 1;
        $group = $this->where($map)->getField('id,title');
        return $group;
    }
}