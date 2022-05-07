<?php

namespace TechTask\ProductBook;

use TechTask\Product\Product;
use TechTask\Column\Column;

class ProductBook extends Product
{
    protected const EXTRA_ATTRIBUTE_TABLE_NAME = 'books';

    protected const EXTRA_ATTRIBUTE_COLUMN_COUNT = 3;

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

    protected static function getExtraColumns(): array
    {
        return [
            new Column('weight', 'float'),
        ];
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

    public function indexCardData(): array
    {
        return [
            $this->getSku(),
            $this->getName(),
            $this->getPrice(),
            "Weight: {$this->getWeight()} KG",
        ];
    }

    public function getWeight()
    {
        return $this->weight;
    }
}
