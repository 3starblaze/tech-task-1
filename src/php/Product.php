<?php

namespace TechTask\Product;

/**
 * Product database model which handles updates in database.
 */
abstract class Product
{
    protected const EXTRA_ATTRIBUTE_INSERT_QUERY = '';

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
     * @param $pdo PDO instance which is used to save the product model.
     * @param $sku Stock keeping unit.
     * @param $price The value of product in cents.
     */
    public function __construct(
        \PDO $pdo,
        string $sku,
        string $name,
        int $price
    ) {
        $isQuerySuccessful
            = $pdo->prepare('INSERT INTO products VALUES(null, ?, ?, ?)')
                  ->execute(array($sku, $name, $price));

        if (!$isQuerySuccessful) {
            die('Product failed to be created!');
        }

        $this->databaseId = $pdo->lastInsertId();
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Helper method that tries to create table with extra attributes and
     * reports query problems if the creation was not successful. Note:
     * databaseId is already provided for the query.
     *
     * @param $pdo The PDO object on which a query is called.
     * @param $args Values that are passed to the query.
     */
    protected function tryCreatingExtraAttributes(\PDO $pdo, array $args)
    {
        $statement = $pdo->prepare(static::EXTRA_ATTRIBUTE_INSERT_QUERY);
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
            $this->extraAttributeId = $pdo->lastInsertId();
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
