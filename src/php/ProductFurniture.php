<?php

namespace TechTask\ProductFurniture;

use TechTask\Product\Product;

class ProductFurniture extends Product
{
    private $furnitureDataId;

    /**
     * Furniture height in cm.
     */
    private $height;

    /**
     * Furniture width in cm.
     */
    private $width;

    /**
     * Furniture length in cm.
     */
    private $length;

    public function __construct(
        \PDO $pdo,
        string $sku,
        string $name,
        int $price,
        int $height,
        int $width,
        int $length
    ) {
        parent::__construct($pdo, $sku, $name, $price);

        $statement = $pdo->prepare(
            'INSERT INTO furniture VALUES(null, ?, ?, ?, ?)'
        );

        $args = array($this->getDatabaseId(), $height, $width, $length);

        if (!$statement->execute($args)) {
            // TODO Destroy Product entry here
            die('ProductFurniture failed to be created!');
        }

        $this->furnitureDataId = $pdo->lastInsertId();
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getLength()
    {
        return $this->length;
    }
}
