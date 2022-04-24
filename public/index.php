<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Hello, World!</h1>
        <?php
        require __DIR__ . '/../vendor/autoload.php';

        use Dotenv\Dotenv;
        use TechTask\Product;
        use TechTask\ProductDisc;
        use TechTask\ProductBook;

        Dotenv::createImmutable(__DIR__.'/../')->load();

        $pdo = new PDO(
            sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
        );

        $product = new Product\Product($pdo, "ABC123123", "Abstract Item", 499);

        print(
            sprintf(
                "SKU: %s\n, name: %s\n, price: %s\n, id: %s\n",
                $product->getSku(),
                $product->getName(),
                $product->getPrice(),
                $product->getDatabaseId(),
        ));

        print("---");

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

        print("---");

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
        <div id="root"></div>
        <script src="./app.bundle.js"></script>
    </body>
</html>
