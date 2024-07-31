<?php

use ZmDesign\AfricanVibesEcommerceBackend\Controllers\ProductController;

require __DIR__ . '/../vendor/autoload.php';


$productController = new ProductController();

$products = $productController->getAllProducts();

echo json_encode($products);
