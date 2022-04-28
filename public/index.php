<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use TechTask\Router\Router;

Dotenv::createImmutable(__DIR__.'/../')->load();
$router = new Router();

$router->registerRoute('/', function() {
    require __DIR__ . '/../src/php/views/index.php';
});

$router->handleRequest();

?>
