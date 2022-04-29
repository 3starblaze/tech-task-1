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
    private static function withPdo(): \PDO
    {
        return self::$pdo ?? die('self::$pdo is not set!');
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
            die('EXTRA_ATTRIBUTE_TABLE_NAME is not defined!');
        }

        if (!static::EXTRA_ATTRIBUTE_COLUMN_COUNT) {
            die('EXTRA_ATTRIBUTE_COLUMN_COUNT is not defined!');
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

        // TODO Hide this information in production
        if (!$statement->execute($executeArgs)) {
            // TODO Destroy Product entry here
            echo('$statement');
            var_dump($statement);
            echo('executeArgs: ');
            var_dump($executeArgs);
            echo('error info: ');
            var_dump($statement->errorInfo());
            die('X failed to be created!');
        } else {
            $this->extraAttributeId = self::withPdo()->lastInsertId();
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

        // TODO Hide this information in production
        if (!$statement->execute(array($this->sku, $this->name, $this->price))) {
            print('Product failed to be created!');
            echo('error info: ');
            var_dump($statement->errorInfo());
            die;
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
            ?? die('Model has no ID because it is not saved!');
    }
}
