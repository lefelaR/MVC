<?php

namespace app\Core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
       return substr($path, 0, $position);
    }

    public function getMethod()
    {

    }
}