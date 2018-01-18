<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{
    protected $_validate = array(
        array('username','require','用户名不得为空'),
        array('password','require','用户名密码不得为空'),
        array('nickname','require','用户昵称不得为空'),
        array('image','require','请上传用户头像'),
        array('phone','require','请填写用户电话号码'),
        array('email','require','请填写用户邮箱')
    );

    public function get_info($user_id){
        $map['id'] = $user_id;
        $map['state'] = array('in',array(1,2));
        $info = $this->where($map)->find();
        return $info;
    }

}