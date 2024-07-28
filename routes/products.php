<?php
require_once '../controllers/ProductController.php';

$productController = new ProductController();

$products = $productController->getAllProducts();

echo json_encode($products);
