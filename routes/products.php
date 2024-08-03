<?php

use ZmDesign\AfricanVibesEcommerceBackend\Controllers\ProductController;

require __DIR__ . '/../vendor/autoload.php';

$idParam = isset($_GET['id']) ? $_GET['id'] : null;

$productController = new ProductController();

if ($idParam === null) {
    $products = $productController->getAllProducts();
    echo json_encode($products);
} else {
    $product = $productController->getSingleProduct($idParam);
    echo json_encode($product);
}
