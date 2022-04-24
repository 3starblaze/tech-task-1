<?php

namespace TechTask\ProductBook;

use TechTask\Product\Product;

class ProductBook extends Product
{
    private $bookDataId;

    /**
     * Book's weight in kg.
     */
    private $weight;

    public function __construct(
        \PDO $pdo,
        string $sku,
        string $name,
        int $price,
        float $weight
    ) {
        parent::__construct($pdo, $sku, $name, $price);

        $statement = $pdo->prepare('INSERT INTO books VALUES(null, ?, ?)');

        if (!$statement->execute(array($this->getDatabaseId(), $weight))) {
            // TODO Destroy Product entry here
            die('ProductBook failed to be created!');
        }

        $this->bookDataId = $pdo->lastInsertId();
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }
}
