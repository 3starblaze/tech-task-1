<?php

namespace TechTask\Product;

/**
 * Product database model which handles updates in database.
 */
abstract class Product
{
    protected const EXTRA_ATTRIBUTE_INSERT_QUERY = '';

    /**
     * \PDO instance that is used to communicate to database.
     */
    private static ?\PDO $pdo = null;

    private $sku;

    private $name;

    private $price;

    /**
     * The product's id in EXTRA_ATTRIBUTE_TABLE_NAME table.
     */
    private $extraAttributeId;

    /**
     * Product constructor.
     *
     * @param $sku Stock keeping unit.
     * @param $price The value of product in cents.
     */
    public function __construct(
        string $sku,
        string $name,
        int $price
    ) {
        $isQuerySuccessful
            = self::withPdo()
            ->prepare('INSERT INTO products VALUES(null, ?, ?, ?)')
            ->execute(array($sku, $name, $price));

        if (!$isQuerySuccessful) {
            die('Product failed to be created!');
        }

        $this->databaseId = self::withPdo()->lastInsertId();
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Return self::$pdo or throw error if it is not set.
     */
    private static function withPdo(): \PDO
    {
        return self::$pdo ?? die('self::$pdo is not set!');
    }

    public static function setPdo(\PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    /**
     * Helper method that tries to create table with extra attributes and
     * reports query problems if the creation was not successful. Note:
     * databaseId is already provided for the query.
     *
     * @param $args Values that are passed to the query.
     */
    protected function tryCreatingExtraAttributes(array $args)
    {
        $statement = self::withPdo()
                   ->prepare(static::EXTRA_ATTRIBUTE_INSERT_QUERY);
        $executeArgs = array_merge(array($this->getDatabaseId()), $args);

        // TODO Hide this information in production
        if (!$statement->execute($executeArgs)) {
            // TODO Destroy Product entry here
            echo('executeArgs: ');
            var_dump($executeArgs);
            echo('error info: ');
            var_dump($statement->errorInfo());
            die('X failed to be created!');
        } else {
            $this->extraAttributeId = self::withPdo()->lastInsertId();
        }
    }

    /**
     * Convert the class to JSON for sending it via API.
     */
    abstract public function toJson();

    public function getSku()
    {
        return $this->sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDatabaseId()
    {
        return $this->databaseId;
    }
}
