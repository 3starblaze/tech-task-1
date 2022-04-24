<?php

namespace TechTask\ProductDisc;

use TechTask\Product\Product;

class ProductDisc extends Product
{
    /**
     * This model's id in `discs` table.
     */
    private $discDataId;

    /**
     * Disc size in MB.
     */
    private $discSize;

    public function __construct(
        \PDO $pdo,
        string $sku,
        string $name,
        int $price,
        int $discSize
    ) {
        parent::__construct($pdo, $sku, $name, $price);

        $statement = $pdo->prepare('INSERT INTO discs VALUES(null, ?, ?)');

        if (!$statement->execute(array($this->getDatabaseId(), $discSize))) {
            // TODO Destroy Product entry here
            die('ProductDisc failed to be created!');
        }

        $this->discDataId = $pdo->lastInsertId();
        $this->discSize = $discSize;
    }

    public function getDiscSize()
    {
        return $this->discSize;
    }
}
