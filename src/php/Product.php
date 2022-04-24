<?php

namespace TechTask\Product;

/**
 * Product database model which handles updates in database.
 */
class Product
{
    private $sku;

    private $price;

    private $databaseId;

    /**
     * Product constructor.
     *
     * @param $pdo PDO instance which is used to save the product model.
     * @param $sku Stock keeping unit.
     * @param $price The value of product in cents.
     */
    public function __construct(\PDO $pdo, string $sku, int $price)
    {
        $isQuerySuccessful
            = $pdo->prepare('INSERT INTO products VALUES(null, ?, ?)')
                  ->execute(array($sku, $price));

        if (!$isQuerySuccessful) {
            die('Product failed to be created!');
        }

        $this->databaseId = $pdo->lastInsertId();
        $this->sku = $sku;
        $this->price = $price;
    }

    public function getSku()
    {
        return $this->sku;
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