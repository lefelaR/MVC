<?php

include 'app/core/Components.php';
$server = $_SERVER["HTTP_HOST"];
$staging = 'localhost:8080';
$ver;
if($server == $staging){
    $ver = 'staging';
}else{
    $ver = 'live';
}

$context = new stdClass;
if($ver == 'staging'){
    $siteroot = '/mvc/public/';
}
if($ver == 'live'){
    $siteroot = '/public/';
}


$context->root = explode('/' , $siteroot);

if(in_array("public", $context->root)){
    header('Location:'.$siteroot);
}else{
    $siteroot = $_SERVER['HTTP_HOST']. $siteroot .'/public/';
}
?>