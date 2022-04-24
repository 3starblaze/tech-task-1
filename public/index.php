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

        Dotenv::createImmutable(__DIR__.'/../')->load();

        $pdo = new PDO(
            sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
        );

        $product = new Product\Product($pdo, "ABC123123", 499);

        print(
            sprintf(
                "SKU: %s\n, price: %s\n, id: %s\n",
                $product->getSku(),
                $product->getPrice(),
                $product->getDatabaseId(),
        ));

        print("---");

        $disc = new ProductDisc\ProductDisc($pdo, "DSC123123", 199, 1000);

        print(
            sprintf(
                "SKU: %s\n, price: %s\n, id: %s, disc size: %s\n",
                $disc->getSku(),
                $disc->getPrice(),
                $disc->getDatabaseId(),
                $disc->getDiscSize(),
            )
        );
        ?>
        <div id="root"></div>
        <script src="./app.bundle.js"></script>
    </body>
</html>
