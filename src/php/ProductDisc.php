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
        string $sku,
        string $name,
        int $price,
        int $discSize
    ) {
        parent::__construct($sku, $name, $price);
        $this->discSize = $discSize;
    }

    protected function getExtraAttributeArgs(): array
    {
        return array($this->discSize);
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
