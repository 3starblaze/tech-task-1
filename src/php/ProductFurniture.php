<?php

namespace TechTask\ProductFurniture;

use TechTask\Product\Product;
use TechTask\Column\Column;

class ProductFurniture extends Product
{
    protected const EXTRA_ATTRIBUTE_TABLE_NAME = 'furniture';

    protected const EXTRA_ATTRIBUTE_COLUMN_COUNT = 5;

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
        string $sku,
        string $name,
        int $price,
        int $height,
        int $width,
        int $length
    ) {
        parent::__construct($sku, $name, $price);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    protected static function getExtraColumns(): array
    {
        return [
            new Column('height', 'int'),
            new Column('width', 'int'),
            new Column('length', 'int'),
        ];
    }

    protected function getExtraAttributeArgs(): array
    {
        return array($this->height, $this->width, $this->length);
    }

    public function toJson()
    {
        return json_encode(array(
            'id' => $this->getDatabaseId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
        ));
    }

    public function indexCardData(): array
    {
        return array_merge(
            parent::indexCardData(),
            [sprintf(
                'Dimensions: %sx%sx%s cm',
                $this->getHeight(),
                $this->getWidth(),
                $this->getLength(),
            )],
        );
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
