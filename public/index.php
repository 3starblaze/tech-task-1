<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use TechTask\Api\Api;
use TechTask\Product\Product;
use TechTask\Router\Router;

function showBaseView(): void
{
    require __DIR__ . '/../src/php/views/base.html';
}

Dotenv::createImmutable(__DIR__ . '/../')->load();
$pdo = new PDO(
    sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
);

Product::registerChildClass('disc', 'TechTask\ProductDisc\ProductDisc');
Product::registerChildClass('book', 'TechTask\ProductBook\ProductBook');
Product::registerChildClass(
    'furniture',
    'TechTask\ProductFurniture\ProductFurniture',
);

Product::setPdo($pdo);
$router = new Router();

$router->registerRoute('/', 'showBaseView');

$router->registerRoute('/add-product', 'showBaseView');

$router->registerRoute('/api/index', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::index();
});

$router->registerRoute('/api/mass_delete', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::massDelete();
});

$router->registerRoute('/api/products/form-data', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::formData();
});

$router->registerRoute('/api/products/new', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo Api::new();
});

$router->handleRequest();

?>
