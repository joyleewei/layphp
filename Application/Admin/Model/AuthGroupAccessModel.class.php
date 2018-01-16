<?php
namespace Admin\Model;
use Think\Model;
class AuthGroupAccessModel extends Model{

    public function get_gid($user_id){
        $map['uid'] = $user_id;
        $gid = $this->where($map)->getField('group_id,uid');
        return array_keys($gid);
    }
}