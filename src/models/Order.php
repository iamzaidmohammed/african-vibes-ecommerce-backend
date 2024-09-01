<?php

namespace app\Models;

use app\Config\Dbh;
use app\Controllers\CartController;

class Order extends Dbh
{
    private $userCart;

    public function __construct()
    {
        parent::__construct();
        $this->userCart = new CartController();
    }

    // Create an order
    public function createOrder($userId, $totalAmount)
    {
        $sql = 'INSERT INTO orders (user_id, total_amount) VALUES (:userId, :total_amount)';
        $params = [':userId' => $userId, ':total_amount' => $totalAmount];

        $this->execute($sql, $params);

        $orderId = $this->lastInsertId();
        $cart = $this->userCart->getUserCart($userId);

        foreach ($cart as $cartItem) {
            $productId = $cartItem['productID'];
            $quantity = $cartItem['quantity'];
            $totalPrice = $cartItem['total'];

            $orderItemsSql = 'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:orderId, :productId, :quantity, :price)';

            $orderItemsParams = [':orderId' => $orderId, ':productId' => $productId, ':quantity' => $quantity, ':price' => $totalPrice];

            $this->execute($orderItemsSql, $orderItemsParams);
        }

        return true;
    }

    public function addOrderItems($userId)
    {
        $orderId = $this->lastInsertId();
        $cart = $this->userCart->getUserCart($userId);

        $productId = $cart[0]['productID'];
        $quantity = $cart[0]['quantity'];
        $totalPrice = $cart[0]['total'];

        $sql = 'INSERT INTO cart_items (order_id, product_id, quantity, price) VALUES (:orderId, :productId, :quantity, :price)';
        $params = [':orderId' => $orderId, ':productId' => $productId, ':quantity' => $quantity, ':price' => $totalPrice];
        return $this->execute($sql, $params);
    }

    public function getOrderDetails($userId)
    {
        $orderSql = "SELECT * FROM orders WHERE user_id = :userId ORDER BY order_id DESC LIMIT 1";
        $orderParams = [':userId' => $userId];
        $order = $this->fetch($orderSql, $orderParams);

        if ($order) {
            $orderId = $order['order_id'];

            $orderItemsSql = "SELECT 
            oi.order_item_id, 
            oi.order_id, 
            p.product_id, 
            p.product_name, 
            oi.price, 
            oi.quantity,
            GROUP_CONCAT(pi.product_image ORDER BY pi.image_id SEPARATOR ',') AS product_images
        FROM 
            order_items oi
        JOIN 
            products p ON p.product_id = oi.product_id
        LEFT JOIN 
            product_images pi ON p.product_id = pi.product_id
        WHERE 
            oi.order_id = :orderId
        GROUP BY 
            oi.order_item_id
        ";

            $orderItemsParams = [':orderId' => $orderId];
            $orderItems = $this->fetchAll($orderItemsSql, $orderItemsParams);
        }

        if ($order && $orderItems) {
            return [
                'order' => $order,
                'orderItems' => $orderItems
            ];
        }
    }
}
