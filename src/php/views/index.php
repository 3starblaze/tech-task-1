<?php

use Dotenv\Dotenv;
use TechTask\Product\Product;
use TechTask\ProductDisc\ProductDisc;
use TechTask\ProductBook\ProductBook;
use TechTask\ProductFurniture\ProductFurniture;
use TechTask\Router\Router;

$pdo = new PDO(
    sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
);

Product::setPdo($pdo);

$products = array(
    new ProductDisc(
        "DSC123123",
        "Copyrighted music",
        199,
        1000
    ),
    new ProductBook(
        "BKK123123",
        "Learn PHP in 24 hours",
        23.99,
        0.5
    ),
    new ProductFurniture(
        "FNT123123",
        "Wooden box",
        99.99,
        120,
        120,
        240
    ),
);

?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>This is Root route.</h1>
        <?php foreach($products as $product): ?>
            <p><?= $product->toJson() ?></p>
        <?php endforeach; ?>

        <div id="root"></div>
        <script src="./app.bundle.js"></script>
    </body>
</html>
