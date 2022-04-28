<?php

use Dotenv\Dotenv;
use TechTask\ProductDisc;
use TechTask\ProductBook;
use TechTask\ProductFurniture;
use TechTask\Router\Router;

$pdo = new PDO(
    sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
);
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>This is Root route.</h1>
        <p>
            <?php
            $disc = new ProductDisc\ProductDisc(
                $pdo,
                "DSC123123",
                "Copyrighted music",
                199,
                1000
            );

            print(
                sprintf(
                    "SKU: %s\n, name: %s\n, price: %s\n, id: %s\n disc size: %s\n",
                    $disc->getSku(),
                    $disc->getName(),
                    $disc->getPrice(),
                    $disc->getDatabaseId(),
                    $disc->getDiscSize(),
                )
            );
            ?>
        </p>
        <p>
            <?php
            $book = new ProductBook\ProductBook(
                $pdo,
                "BKK123123",
                "Learn PHP in 24 hours",
                23.99,
                0.5
            );

            print(
                sprintf(
                    "SKU: %s\n, name: %s\n price: %s\n, id: %s, weight: %s\n",
                    $book->getSku(),
                    $book->getName(),
                    $book->getPrice(),
                    $book->getDatabaseId(),
                    $book->getWeight(),
                )
            );
            ?>
        </p>
        <p>
            <?php
            $furniture = new ProductFurniture\ProductFurniture(
                $pdo,
                "FNT123123",
                "Wooden box",
                99.99,
                120,
                120,
                240
            );

            print(
                sprintf(
                    "SKU: %s\n, name: %s\n price: %s\n, id: %s, HxWxL: %sx%sx%s\n",
                    $furniture->getSku(),
                    $furniture->getName(),
                    $furniture->getPrice(),
                    $furniture->getDatabaseId(),
                    $furniture->getHeight(),
                    $furniture->getWidth(),
                    $furniture->getLength(),
                )
            );
            ?>
        </p>
        <div id="root"></div>
        <script src="./app.bundle.js"></script>
    </body>
</html>
