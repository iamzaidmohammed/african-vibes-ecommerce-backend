<?php

use app\Controllers\ShippingController;
use app\Controllers\AuthController;
use app\Controllers\OrderController;

require __DIR__ . '/../../vendor/autoload.php';

$shippingController = new ShippingController();
$authController = new AuthController();
$orderController = new OrderController();

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "";

    $userId = htmlspecialchars($input['user_id'] ?? '');
    $firstName = htmlspecialchars($input['first_name'] ?? '');
    $lastName = htmlspecialchars($input['last_name'] ?? '');
    $email = htmlspecialchars($input['email'] ?? '');
    $phone = htmlspecialchars($input['phone'] ?? '');
    $detailedAddress = htmlspecialchars($input['detailed_address'] ?? '');
    $country = htmlspecialchars($input['country'] ?? '');
    $city = htmlspecialchars($input['city'] ?? '');
    $province = htmlspecialchars($input['province'] ?? '');
    $postalCode = htmlspecialchars($input['postal_code'] ?? '');

    if (empty($userId) || empty($detailedAddress) || empty($country) || empty($city) || empty($province) || empty($postalCode)) {
        $errorMessage = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $firstName) || !preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
        $errorMessage = "Name contains invalid characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Invalid email address';
    } elseif (!preg_match('/^\d+$/', $phone)) {
        $errorMessage = 'Phone number cannot contain letters';
    }

    if (!empty($errorMessage)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    } else {
        $addressExists = $shippingController->checkAddress($userId);

        if ($addressExists) {
            $address = $shippingController->getUserAddress($userId);

            // Check if the address matches the input
            if (
                $address[0]['firstName'] === $firstName &&
                $address[0]['lastName'] === $lastName &&
                $address[0]['email'] === $email &&
                $address[0]['phone'] === $phone &&
                $address[0]['detailedAddress'] === $detailedAddress &&
                $address[0]['country'] === $country &&
                $address[0]['province'] === $province &&
                $address[0]['city'] === $city &&
                $address[0]['postalCode'] === $postalCode
            ) {
                http_response_code(200);
                // echo json_encode(['status' => 'success']);
            } else {
                $authController->updateUserDetails($userId, $firstName, $lastName, $email, $phone);
                $shippingController->updateAddress($userId, $detailedAddress, $country, $province, $city, $postalCode);
                http_response_code(200);
                // echo json_encode(['status' => 'success']);
            }
        } else {
            $shippingController->createAddress($userId, $detailedAddress, $country, $province, $city, $postalCode);
            http_response_code(201);
        }

        $orderController->updateOrderItems($userId);
        echo json_encode(['status' => 'success']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $address = $shippingController->getUserAddress($userId);

    echo json_encode($address);
}
