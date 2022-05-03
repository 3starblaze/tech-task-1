<?php

namespace TechTask\Product;

use TechTask\Util\Util;

/**
 * Product database model which handles updates in database.
 */
abstract class Product
{
    /**
     * The table name of *this* class.
     * @see static::EXTRA_ATTRIBUTE_TABLE_NAME
     */
    protected const BASE_TABLE_NAME = 'products';

    /**
     * The column count for *this* class.
     * @see static::EXTRA_ATTRIBUTE_COLUMN_COUNT
     */
    protected const BASE_COLUMN_COUNT = 4;

    /**
     * The name of the table in which extra attributes are stored.
     */
    protected const EXTRA_ATTRIBUTE_TABLE_NAME = null;

    /**
     * The number of columns in extra attributes table, including the id.
     */
    protected const EXTRA_ATTRIBUTE_COLUMN_COUNT = null;

    protected static array $extraColumns = [];

    /**
     * \PDO instance that is used to communicate to database.
     */
    private static ?\PDO $pdo = null;

    private $sku;

    private $name;

    private $price;

    /**
     * The id of this class' table or null if the model is not in database.
     */
    private ?int $databaseId = null;

    /**
     * The product's id in EXTRA_ATTRIBUTE_TABLE_NAME table.
     */
    private $extraAttributeId;

    /**
     * Product constructor.
     *
     * @param $sku Stock keeping unit.
     * @param $price The value of product in cents.
     */
    public function __construct(
        string $sku,
        string $name,
        int $price
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Return self::$pdo or throw error if it is not set.
     */
    protected static function withPdo(): \PDO
    {
        return self::$pdo ?? Util::throwError('self::$pdo is not set!');
    }

    public static function setPdo(\PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    /**
     * Return a query that is used to insert an entry into extra attributes
     * table.
     */
    protected function extraAttributesQuery(): string
    {
        if (!static::EXTRA_ATTRIBUTE_TABLE_NAME) {
            Util::throwError('EXTRA_ATTRIBUTE_TABLE_NAME is not defined!');
        }

        if (!static::EXTRA_ATTRIBUTE_COLUMN_COUNT) {
            Util::throwError('EXTRA_ATTRIBUTE_COLUMN_COUNT is not defined!');
        }

        return Util::formatInsertQuery(
            static::EXTRA_ATTRIBUTE_TABLE_NAME,
            static::EXTRA_ATTRIBUTE_COLUMN_COUNT,
        );
    }

    /**
     * Helper method that tries to create table with extra attributes and
     * reports query problems if the creation was not successful. Note:
     * databaseId is already provided for the query.
     *
     * @param $args Values that are passed to the query.
     */
    protected function tryCreatingExtraAttributes(): void
    {
        $statement = self::withPdo()->prepare($this->extraAttributesQuery());
        $executeArgs = array_merge(
            array($this->getDatabaseId()),
            $this->getExtraAttributeArgs(),
        );

        if (!$statement->execute($executeArgs)) {
            // TODO Destroy Product entry here
            Util::throwError('Extra attribute row creation failed!');
        } else {
            $this->extraAttributeId = self::withPdo()->lastInsertId();
        }
    }

    /**
     * Initialize databaseId for a model with no databaseId.
     */
    protected function setDatabaseId(int $id): void
    {
        if ($this->databaseId == null) {
            $this->databaseId = $id;
        } else {
            Util::throwError('Id has already been set!');
        }
    }

    /**
     * Return array of arguments that is used to create extra attribute table.
     * This array should not contain databaseId since it will be provided
     * automatically.
     */
    abstract protected function getExtraAttributeArgs(): array;

    /**
     * Convert the class to JSON for sending it via API.
     */
    abstract public function toJson();

    public function save(): void
    {
        $statement = self::withPdo()->prepare(
            Util::formatInsertQuery(
                static::BASE_TABLE_NAME,
                static::BASE_COLUMN_COUNT,
            )
        );

        if (!$statement->execute(array($this->sku, $this->name, $this->price))) {
            Util::throwError('Product failed to be created!');
        } else {
            $this->databaseId = self::withPdo()->lastInsertId();
        }

        $this->tryCreatingExtraAttributes();
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDatabaseId(): int
    {
        return $this->databaseId
            ?? Util::throwError('Model has no ID because it is not saved!');
    }
}
