<?php
namespace Api\Controller;
use Think\Controller;
class VerifyController extends Controller{
    public function code(){
        $config =    array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
}