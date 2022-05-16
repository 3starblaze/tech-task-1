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

    /**
     * Convert cents into dollars and make it human-readable.
     */
    public static function formatCents(int $cents): string
    {
        return sprintf('%.2f $', $cents / 100);
    }

    /**
     * Return function that takes a value and returns it unchanged.
     */
    public static function getIdentity()
    {
        return function ($val) {
            return $val;
        };
    }

    public static function showBaseView(): void
    {
        require __DIR__ . '/views/base.html';
    }

    /**
     * Return a function that calls $renderer and displays it as JSON.
     */
    public static function makeJsonHandler(callable $renderer): callable
    {
        return function () use ($renderer) {
            header('Content-Type: application/json; charset=utf-8');
            echo $renderer();
        };
    }
}
