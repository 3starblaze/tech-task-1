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

        $result = [];
        // TODO store product classes somewhere else to avoid hard-coded classes
        $products = array_merge(
            ProductDisc::all(),
            ProductBook::all(),
            ProductFurniture::all(),
        );

        foreach ($products as $p) {
            $result[$p->getDatabaseId()] = [
                'indexCardData' => $p->indexCardData(),
            ];
        }

        return json_encode($result);
    }
}
