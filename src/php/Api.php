<?php

namespace TechTask\Api;

use TechTask\Product\Product;

class Api
{
    /**
     * Return index page rendering information in JSON.
     */
    public static function index(): string
    {
        $result = [];
        $products = Product::all();

        foreach ($products as $p) {
            $result[$p->getDatabaseId()] = [
                'indexCardData' => $p->indexCardData(),
            ];
        }

        return json_encode($result);
    }

    public static function massDelete(): string
    {

        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        return json_encode([
            'status' => 'ok',
            'data' => $obj,
        ]);
    }
}
