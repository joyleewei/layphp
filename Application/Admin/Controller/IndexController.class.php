<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class IndexController extends AdminbaseController {
    private $user_model;
    public function __construct(){
        parent::__construct();
        $this->user_model = D('Admin/user');
    }
    public function index(){
        $user_id = $_SESSION['user_info']['id'];
        $map['id'] = $user_id;
        $user_info = $this->user_model->where($map)->find();
        $this->assign('user_info',$user_info);
        $this->display();
    }

    public function main(){
        $this->display();
    }

    public function area(){
        for($i=0;$i<30;$i++){
            $start = $i*500;
            $end = $start + 500;
            $map['tree'] = '';
            $area = M('area')->where($map)->limit($start,$end)->getField('id,position');
            $data = array();
            if(!empty($area)){
                foreach($area as $k=>$v){
                    if(!empty($v)){
                        $value = explode(' ',$v);
                        $str = join(',',array_map('sub',$value));
                        if(empty($str)){
                            $str = '0';
                        }
                        $map_area['id'] = $k;
                        M('area')->where($map_area)->setField('tree',$str);
                        unset($map_area);
                        echo M('area')->getLastSql().'<br />';
                    }

                }
            }

        }

    }

}