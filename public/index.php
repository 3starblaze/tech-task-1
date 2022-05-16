<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use TechTask\Api\Api;
use TechTask\Product\Product;
use TechTask\Router\Router;
use TechTask\Util\Util;

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

$router->registerRoute('/', ['TechTask\Util\Util', 'showBaseView']);

$router->registerRoute('/add-product', ['TechTask\Util\Util', 'showBaseView']);

$router->registerRoute(
    '/api/index',
    Util::makeJsonHandler(['TechTask\Api\Api', 'index']),
);

$router->registerRoute(
    '/api/mass_delete',
    Util::makeJsonHandler(['TechTask\Api\Api', 'massDelete']),
);
$router->registerRoute(
    '/api/products/form-data',
    Util::makeJsonHandler(['TechTask\Api\Api', 'formData']),
);
$router->registerRoute('/api/products/new', function () {
    Api::new();
    header("Location: /");
});

$router->handleRequest();
