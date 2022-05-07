<?php

namespace TechTask\ProductDisc;

use TechTask\Product\Product;
use TechTask\Column\Column;

class ProductDisc extends Product
{
    protected const EXTRA_ATTRIBUTE_TABLE_NAME = 'discs';

    protected const EXTRA_ATTRIBUTE_COLUMN_COUNT = 3;

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

    protected static function getExtraColumns(): array
    {
        return [
            new Column('disc_size', 'int'),
        ];
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

    public function indexCardData(): array
    {
        return [
            $this->getSku(),
            $this->getName(),
            $this->getPrice(),
            "Size: {$this->getDiscSize()} MB",
        ];
    }

    public function getDiscSize()
    {
        return $this->discSize;
    }
}
