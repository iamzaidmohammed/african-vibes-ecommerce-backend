<?php

use app\Controllers\OrderController;
use app\Controllers\CartController;
use app\Controllers\PaymentController;

require_once __DIR__ . '/../init.php';
require __DIR__ . '/../../vendor/autoload.php';

$orderController = new OrderController();
$cartController = new CartController();
$paymentController = new PaymentController();

function verifyPayment($reference)
{
    $paystackSecretKey = $_ENV['PAYSTACK_SECRET_KEY'];

    $url = "https://api.paystack.co/transaction/verify/{$reference}";

    // echo $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$paystackSecretKey}",
        "Cache-Control: no-cache",
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // echo $response;

    return json_decode($response, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $reference = $input['reference'];

    $verificationResult = verifyPayment($reference);

    if ($verificationResult['data']['status'] === 'success') {
        $orderId = $input['orderId'];
        $cartId = $input['cartId'];
        $method = $verificationResult['data']['channel'];
        $amount = $verificationResult['data']['amount'] / 100;
        $status = 'Completed';
        $date = $verificationResult['data']['paid_at'];
        $reference = $verificationResult['data']['reference'];

        $orderController->updateOrderStatus($orderId);

        $cartController->emptyCart($cartId);

        $paymentController->addPayment($orderId, $reference, $method, $amount, $status, $date);

        echo json_encode([
            'status' => 'success',
            'message' => 'Payment verified successfully.',
            'data' => $verificationResult['data'],
        ]);
    } else {
        echo json_encode([
            'status' => 'failure',
            'message' => 'Payment verification failed.',
        ]);
    }
}
