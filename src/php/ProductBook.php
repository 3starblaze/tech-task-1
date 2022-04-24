<?php

namespace TechTask\ProductBook;

use TechTask\Product\Product;

class ProductBook extends Product
{
    protected const EXTRA_ATTRIBUTE_INSERT_QUERY
        = 'INSERT INTO discs VALUES(null, ?, ?)';

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

        $this->tryCreatingExtraAttributes($pdo, array($weight));
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }
}
