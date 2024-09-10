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

        $results = [];

        if ($orderDetails) {
            $order = [
                'orderId' => $orderDetails['order']['order_id'],
                'userId' => $orderDetails['order']['user_id'],
                'totalAmount' => $orderDetails['order']['total_amount'],
                'createdAt' => $orderDetails['order']['created_at'],
                'status' => $orderDetails['order']['status'],
            ];

            $orderItems = [];
            foreach ($orderDetails['orderItems'] as $item) {
                $orderItems[] = [
                    'orderItemsId' => $item['order_item_id'],
                    'orderId' => $item['order_id'],
                    'productId' => $item['product_id'],
                    'productName' => $item['product_name'],
                    'productImages' => $item['product_images'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ];
            }

            $results = [
                'order' => $order,
                'orderItems' => $orderItems,
            ];

            return $results;
        } else {
            return [];
        }
    }


    public function updateOrderItems($userId)
    {
        return $this->orderModel->updateOrderItems($userId);
    }

    public function updateOrderStatus($orderId)
    {
        return $this->orderModel->updateOrderStatus($orderId);
    }
}
