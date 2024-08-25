<?php

use app\Controllers\ProductController;

require __DIR__ . '/../../vendor/autoload.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

$productController = new ProductController();

// $product = $productController->getSingleProduct(28);
// echo json_encode($product);

if ($id === null) {
    $products = $productController->getAllProducts();
    echo json_encode($products);
} else {
    $product = $productController->getSingleProduct($id);
    echo json_encode($product);
}
