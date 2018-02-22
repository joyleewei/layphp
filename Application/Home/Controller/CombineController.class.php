<?php
namespace Home\Controller;
use Think\Controller;
class CombineController extends Controller {
    public function index(){
        $data = M('Combine_category')->field('id,name,pid,img')->select();
        $nav = \Org\Nx\Data::channelNav($data,0,'id');
        $nav_data['code'] = 0;
        $nav_data['msg'] = 'success';
        $nav_data['data'] = $nav;
        echo json_encode($nav_data);
    }
}