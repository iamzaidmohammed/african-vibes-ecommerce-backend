<?php

namespace app\Controllers;

use app\Models\Payment;

class PaymentController
{
    private $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
    }

    public function addPayment($orderId, $reference, $method, $amount, $status, $date)
    {
        return $this->paymentModel->addPayment($orderId, $reference, $method, $amount, $status, $date);
    }
}
