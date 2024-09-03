<?php

use app\Controllers\OrderController;

require __DIR__ . '/../../vendor/autoload.php';

$orderController = new OrderController();

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];

    $orders = $orderController->getOrderDetails($userId);

    http_response_code(200);
    echo json_encode($orders);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $input['userId'];
    $totalAmount = $input['totalAmount'];

    $orderController->createOrder($userId, $totalAmount);

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Order created successfully.']);
}

// if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
//     $userId = $input['userId'];

//     $orderController->updateOrderItems($userId);

//     http_response_code(200);
//     echo json_encode(['status' => 'success']);
// }
