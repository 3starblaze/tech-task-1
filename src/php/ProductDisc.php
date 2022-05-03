<?php

namespace TechTask\ProductDisc;

use TechTask\Product\Product;

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

    public static function fromId(int $id): ProductDisc
    {
        $baseTable = static::BASE_TABLE_NAME;
        $extraTable = static::EXTRA_ATTRIBUTE_TABLE_NAME;

        $baseColumns = ['name', 'sku', 'price'];
        $extraColumns = ['disc_size'];

        $selectArgs = implode(', ', array_merge(
            array_map(function (string $val) {
                return 'base.' . $val;
            }, $baseColumns),
            array_map(function (string $val) {
                return 'extra.' . $val;
            }, $extraColumns),
        ));

        // Interpolation is safe because no user-provided data is used.
        $queryString = "SELECT $selectArgs FROM $extraTable extra"
                     . " LEFT JOIN $baseTable base"
                     . " ON extra.product_id=base.id"
                     . " WHERE base.id = ?";

        $statement = self::withPDO()->prepare($queryString);
        $statement->execute([$id]);

        if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            $product = new ProductDisc(
                $row['sku'],
                $row['name'],
                intval($row['price']),
                intval($row['disc_size']),
            );
            $product->setDatabaseId($id);
            return $product;
        } else {
            die('ROW COUNT IS NOT 1!');
        }
    }

    public static function all(): array
    {
        $extraTable = static::EXTRA_ATTRIBUTE_TABLE_NAME;

        return array_map(
            function (array $res) {
                return static::fromId($res[0]);
            },
            static::withPDO()
            ->query("SELECT product_id FROM $extraTable")
            ->fetchAll(),
        );
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
