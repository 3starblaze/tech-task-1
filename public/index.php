<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Hello, World!</h1>
        <?php
        require __DIR__ . '/../vendor/autoload.php';

        use Dotenv\Dotenv;

        Dotenv::createImmutable(__DIR__.'/../')->load();

        $pdo = new PDO(
            sprintf("mysql:dbname=%s;host=%s", $_ENV['DB_NAME'], $_ENV['DB_HOST']),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
        );

        echo("Great success!");
        ?>
        <div id="root"></div>
        <script src="./app.bundle.js"></script>
    </body>
</html>
