<?php

namespace TechTask\Api;

use TechTask\Product\Product;

/**
 * Handle requests for API routes. In MVC terminology this would be a
 * controller for Product class.
 */
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

    public static function new(): string
    {
        $data = $_POST;
        $class = Product::getChildClasses()[$data['productType']];
        $inst = $class::requestToInstance($data);
        $inst->save();
        return $inst->toJson();
    }

    public static function massDelete(): string
    {

        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        foreach ($obj->ids as $id) {
            Product::fromId($id)->delete();
        }

        return json_encode($obj);
    }

    public static function formData(): string
    {
        $productData = array_map(
            function (string $identifier, string $class) {
                return [
                    'productIdentifier' => $identifier,
                    'formSelectValue' => $class::getFormSelectValue(),
                    'fields' => $class::getExtraFields(),
                    'productDescription' => $class::getFormDescription(),
                ];
            },
            array_keys(Product::getChildClasses()),
            Product::getChildClasses(),
        );

        return json_encode([
            'baseFields' => Product::getBaseFields(),
            'productData' => $productData,
        ]);
    }
}
