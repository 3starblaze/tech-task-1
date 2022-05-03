<?php

namespace TechTask\Column;

use TechTask\Util\Util;

/**
 * Container that specifies the column of a table.
 */
class Column
{
    private string $name;

    private string $type;

    /**
     * List of allowed values for $type.
     */
    private static array $validTypes = ['string', 'int', 'float'];

    public function __construct(string $name, string $type)
    {
        $this->name = $name;

        if (in_array($type, static::$validTypes, true)) {
            $this->type = $type;
        } else {
            Util::throwError("type '$type' is not valid!");
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Transform $val into appropriate PHP type that is specified in $this->type.
     *
     * @param $val Value to transform.
     */
    public function convertValue(string $val)
    {
        switch ($this->type) {
            case 'string':
                return $val;
                break;
            case 'int':
                return intval($val);
                break;
            case 'float':
                return floatval($val);
                break;
            default:
                Util::throwError("Cannot convert type '{$this->type}'!");
        }
    }
}
