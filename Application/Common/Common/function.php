<?php
function pr($data){
    echo '<br />';
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '<br />';
}

function sub($value){
    return substr($value,3);
}