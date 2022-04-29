<?php

namespace TechTask\Util;

/**
 * A collection of utility functions.
 */
class Util
{
    /**
     * Generate insert query.
     *
     * @param $tableName *Sanitized* table name.
     * @param $valueCount The number of values to be inserted (including id).
     */
    public static function formatInsertQuery(
        string $tableName,
        int $valueCount
    ): string {
        return sprintf(
            "INSERT INTO $tableName VALUES(%s)",
            implode(", ", array_merge(
                ['null'],
                array_fill(1, $valueCount - 1, "?"),
            )),
        );
    }

    /**
     * Make and throw and Exception.
     *
     * @see \Exception
     */
    public static function throwError(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        // TODO Hide exception info in production
        throw new \Exception($message, $code, $previous);
    }
}
