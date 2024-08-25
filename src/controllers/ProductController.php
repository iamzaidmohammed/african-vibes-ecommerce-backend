<?php

namespace app\Controllers;

use app\Models\Product;

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function getAllProducts()
    {
        $products = $this->productModel->getAllProducts();



        if ($products) {
            $results = [];
            foreach ($products as $product) {
                $results[] = [
                    'id' => $product['product_id'],
                    'categoryId' => $product['category_id'],
                    'name' => $product['product_name'],
                    'price' => $product['price'],
                    'stock' => $product['stock_quantity'],
                    'imgs' => $product['product_images'],
                ];
            }
            return $results;
        } else {
            return [];
        }
    }


    public function getSingleProduct($id)
    {
        $product = $this->productModel->getSingleProduct($id);

        $result = [];
        if ($product) {
            $result[] = [
                'id' => $product['product_id'],
                'artisanId' => $product['artisan_id'],
                'categoryId' => $product['category_id'],
                'name' => $product['product_name'],
                'desc' => $product['product_description'],
                'code' => $product['product_code'],
                'price' => $product['price'],
                'stock' => $product['stock_quantity'],
                'instructions' => $product['care_instructions'],
                'imgs' => $product['product_images'],
            ];
            return $result;
        } else {
            return [];
        }
    }
}
