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
        string $sku,
        string $name,
        int $price,
        float $weight
    ) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }

    protected function getExtraAttributeArgs(): array
    {
        return array($this->weight);
    }

    public function toJson()
    {
        return json_encode(array(
            'id' => $this->getDatabaseId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'weight' => $this->getWeight(),
        ));
    }

    public function getWeight()
    {
        return $this->weight;
    }
}
