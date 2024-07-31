<?php

namespace ZmDesign\AfricanVibesEcommerceBackend\Controllers;

use ZmDesign\AfricanVibesEcommerceBackend\Models\Product;

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
                    'desc' => $product['description'],
                    'img' => $product['product_image'],
                    'price' => $product['price'],
                    'stock' => $product['stock_quantity'],
                ];
            }

            // echo json_encode($results);
            return $results;
        }
    }


    public function getSingleProduct($id)
    {
        return $this->productModel->getSingleProduct($id);
    }
}
