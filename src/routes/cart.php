<?php

use app\Controllers\CartController;

require __DIR__ . '/../../vendor/autoload.php';

$cartController = new CartController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents("php://input"), true);

    $productId = $input['product_id'];
    $userId = $input['user_id'];
    $quantity = $input['quantity'];

    $cartController->addCart($userId, $productId, $quantity);

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $input = json_decode(file_get_contents("php://input"), true);

    $productId = $input['product_id'];
    $quantity = $input['quantity'];

    $cartController->updateCart($productId, $quantity);

    echo json_encode(['status' => 'success', 'message' => 'Cart item updated successfully.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {


    $userId = $_GET['id'];

    $cartItems = $cartController->getUserCart($userId);

    echo json_encode($cartItems);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);
    $userId = $input['user_id'];
    $productId = $input['product_id'];
    $cartController->removeCart($userId, $productId);

    echo json_encode(['status' => 'success', 'message' => 'Cart item removed successfully.']);
}
