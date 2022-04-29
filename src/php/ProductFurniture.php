<?php

namespace TechTask\ProductFurniture;

use TechTask\Product\Product;

class ProductFurniture extends Product
{
    protected const EXTRA_ATTRIBUTE_INSERT_QUERY
        = 'INSERT INTO furniture VALUES(null, ?, ?, ?, ?)';

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

        $this->tryCreatingExtraAttributes(array($height, $width, $length));

        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
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
