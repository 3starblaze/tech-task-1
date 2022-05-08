<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use TechTask\Api\Api;
use TechTask\Product\Product;
use TechTask\Router\Router;

Dotenv::createImmutable(__DIR__.'/../')->load();
$pdo = new PDO(
    sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
);

$childrenClasses = [
    'TechTask\ProductDisc\ProductDisc',
    'TechTask\ProductBook\ProductBook',
    'TechTask\ProductFurniture\ProductFurniture',
];

foreach ($childrenClasses as $class) {
    Product::registerChildClass($class);
}

Product::setPdo($pdo);
$router = new Router();

$router->registerRoute('/', function() {
    require __DIR__ . '/../src/php/views/index.php';
});

$router->registerRoute('/api/index', function() {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::index();
});

$router->registerRoute('/api/mass_delete', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::massDelete();
});

$router->handleRequest();

?>
