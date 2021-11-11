<?php


$context = new stdClass;
$context->siteroot = '/mvc/public';
function url($string)
{
    global $context;
    if (strpos($string, '.')) {
        $string = '/' . $string;
    }
    echo $context->siteroot. $string;
}

