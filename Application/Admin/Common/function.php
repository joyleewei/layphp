<?php
function pr($data){
    if(is_array($data)){
        echo '------------------------<br />';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '------------------------<br />';
    }else{
        echo '------------------------<br />';
        echo $data;
        echo '------------------------<br />';
    }

}
