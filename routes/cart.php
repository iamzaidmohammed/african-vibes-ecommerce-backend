<?php

use ZmDesign\AfricanVibesEcommerceBackend\Controllers\CartController;

require_once __DIR__ . '/../vendor/autoload.php';

$cartController = new CartController();

// $items = $cartController->getAllCarts();

// echo json_encode($items);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents("php://input"), true);

    $productId = $input['product_id'];
    $userId = $input['user_id'];
    $quantity = $input['quantity'];

    $cartController->addCart($userId, $productId, $quantity);

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['getCartItems']) === true) {
    $cartItems = $cartController->getAll();

    // var_dump($cartItems);
    echo json_encode($cartItems);
}
