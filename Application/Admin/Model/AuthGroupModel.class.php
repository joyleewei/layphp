<?php
namespace Admin\Model;
use Think\Model;
class AuthGroupModel extends Model{
    //自动验证
    protected $_validate = array(
        array('title', 'require', '用户组名称不能为空！'),

    );
}