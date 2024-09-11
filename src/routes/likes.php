<?php

use app\Controllers\LikeController;

$likeController = new LikeController();

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $likeStatus = $input['like'];
    $userId = $input['userId'];
    $productId = $input['productId'];

    $likedProducts = $likeController->getLikedProducts($userId);

    if ($likeStatus) {
        $likeController->createLike($userId, $productId);
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist.']);
    } else {
        $likeController->deleteLike($userId, $productId);
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Product removed from wishlist.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];

    $likedProducts = $likeController->getLikedProducts($userId);

    http_response_code(200);
    echo json_encode($likedProducts);
}
