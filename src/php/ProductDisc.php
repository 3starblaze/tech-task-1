<?php

namespace TechTask\ProductDisc;

use TechTask\Product\Product;

class ProductDisc extends Product
{
    protected const EXTRA_ATTRIBUTE_INSERT_QUERY
        = 'INSERT INTO discs VALUES(null, ?, ?)';

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

        $this->tryCreatingExtraAttributes($pdo, array($discSize));
        $this->discSize = $discSize;
    }

    public function toJson()
    {
        return json_encode(array(
            'id' => $this->getDatabaseId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'discSize' => $this->getDiscSize(),
        ));
    }

    public function getDiscSize()
    {
        return $this->discSize;
    }
}
