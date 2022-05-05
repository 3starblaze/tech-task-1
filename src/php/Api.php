<?php

namespace TechTask\Api;

use TechTask\ProductDisc\ProductDisc;

class Api
{
    /**
     * Return index page rendering information in JSON.
     */
    public static function index(): string
    {
        return json_encode(
            array_map(function (ProductDisc $p) {
                return [
                    'databaseId' => $p->getDatabaseId(),
                    'cardTemplate' => $p->indexCard(),
                ];
            }, ProductDisc::all())
        );
    }
}
