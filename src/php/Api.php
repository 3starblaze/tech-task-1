<?php

namespace TechTask\Api;

use TechTask\Product\Product;
use TechTask\ProductBook\ProductBook;
use TechTask\ProductDisc\ProductDisc;
use TechTask\ProductFurniture\ProductFurniture;

class Api
{
    /**
     * Return index page rendering information in JSON.
     */
    public static function index(): string
    {
        return json_encode(array_map(
            function (Product $p) {
                return [
                    'id' => $p->getDatabaseId(),
                    'cardTemplate' => $p->indexCard(),
                ];
            },
            // TODO store product classes somewhere else to avoid hard-coded
            // classes
            array_merge(
                ProductDisc::all(),
                ProductBook::all(),
                ProductFurniture::all(),
            )
        ));
    }
}
