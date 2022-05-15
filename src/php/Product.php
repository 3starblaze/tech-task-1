<?php

namespace TechTask\Product;

use TechTask\Util\Util;
use TechTask\Column\Column;
use TechTask\Field\Field;

/**
 * Product database model which handles updates in database.
 *
 * When making children of this class, make sure to register them via
 * `registerChildClass()`, so that you can use static methods that operate on
 * all classes.
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

    /**
     * \PDO instance that is used to communicate to database.
     */
    private static ?\PDO $pdo = null;

    /**
     * String array of namespaced class names that are children of Product.
     */
    private static array $childrenClasses = [];

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

    protected static function getBaseColumns(): array
    {
        return [
            new Column('sku', 'string'),
            new Column('name', 'string'),
            new Column('price', 'int'),
        ];
    }

    /**
     * Return whether the class is Product (and not just a child).
     */
    protected static function isBase(): bool
    {
        return get_class() == get_called_class();
    }

    /**
     * Returns an array of columns that corresponds to this class's extra
     * attribute table's columns.
     *
     * Note: This function's return array should not contain id or product_id
     * column.
     *
     * @return Column[]
     */
    abstract protected static function getExtraColumns(): array;

    abstract public static function getExtraFields(): array;

    /**
     * Return description that is rendered along the extra fields.
     *
     * @see getExtraFields Information that this function's return value
     * describes.
     */
    abstract public function getFormDescription(): string;

    /**
     * Return the display value of option in product `select` element.
     */
    abstract public static function getFormSelectValue(): string;

    public static function setPdo(\PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    /**
     * Get a Product whose databaseId is $id.
     *
     * Throws error if there is no row with specified $id.
     *
     * @param $id The database id of the requested product.
     */
    public static function fromId(int $id): ?Product
    {
        if (static::isBase()) {
            foreach (static::$childrenClasses as $class) {
                $productCandidate = $class::fromId($id);
                if ($productCandidate) {
                    return $productCandidate;
                }
            }
            return null;
        }

        $baseTable = static::BASE_TABLE_NAME;
        $extraTable = static::EXTRA_ATTRIBUTE_TABLE_NAME;
        $baseColumns = static::getBaseColumns();
        $extraColumns = static::getExtraColumns();

        $selectArgs = implode(', ', array_merge(
            array_map(function (Column $val) {
                return 'base.' . $val->getName();
            }, $baseColumns),
            array_map(function (Column $val) {
                return 'extra.' . $val->getName();
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

            $product = new static(...array_map(
                function (Column $col) use ($row) {
                    return $col->convertValue($row[$col->getName()]);
                },
                array_merge($baseColumns, $extraColumns),
            ));
            $product->setDatabaseId($id);
            return $product;
        } else {
            return null;
        }
    }

    /**
     * Get all objects of this class which are defined in the table.
     *
     * @return Product[]
     */
    public static function all(): array
    {
        if (static::isBase()) {
            return array_merge(...array_map(function (string $class) {
                return $class::all();
            }, static::$childrenClasses));
        } else {
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
    }

    public static function getBaseFields()
    {
        return [
          new Field('SKU', 'sku', 'sku'),
          new Field('Name', 'name', 'name'),
          new Field('Price ($)', 'price', 'price', [
              'type' => 'number',
              'step' => '0.01',
          ]),
        ];
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

    /**
     * Return a list of data that should be shown in the index card.
     */
    public function indexCardData(): array
    {
        return [
            $this->getSku(),
            $this->getName(),
            Util::formatCents($this->getPrice()),
        ];
    }

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

    /**
     * Register a child class which is needed for internal purposes.
     *
     * @param $class Namespaced name of the class.
     */
    public function registerChildClass(string $identifier, string $class): void
    {
        if (array_key_exists($identifier, static::$childrenClasses)) {
            Util::throwError(
                "Identifier '$identifier' has already been registered!"
            );
        }
        static::$childrenClasses[$identifier] = $class;
    }

    public function getChildClasses(): array
    {
        return static::$childrenClasses;
    }

    public function delete(): void
    {
        $extraAttributeStatement = self::withPDO()->prepare(
            sprintf(
                'DELETE FROM %s WHERE product_id=?',
                static::EXTRA_ATTRIBUTE_TABLE_NAME,
            )
        );
        $baseStatement = self::withPDO()->prepare(
            sprintf(
                'DELETE FROM %s WHERE id=?',
                static::BASE_TABLE_NAME,
            )
        );

        $extraAttributeStatement->execute([$this->getDatabaseId()]);
        $baseStatement->execute([$this->getDatabaseId()]);
    }
}
