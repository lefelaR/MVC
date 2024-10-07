<?php

// Routing
require_once __DIR__ ."/vendor/autoload.php";
use app\Core\Application;

// classes
$app = new Application();

$app->router->get("/", function ()  {
    return 'hello';
});

$app->router->get('/contact', function () {
    return 'Contact';
});


$app->run();
