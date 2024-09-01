<?php

namespace app\Controllers;

use app\Models\Order;

class OrderController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    public function createOrder($userId, $totalAmount)
    {
        return $this->orderModel->createOrder($userId, $totalAmount);
    }

    public function getOrderDetails($userId)
    {
        $orderDetails = $this->orderModel->getOrderDetails($userId);

        if ($orderDetails) {
            return $orderDetails;
        } else {
            return [];
        }
    }
}
