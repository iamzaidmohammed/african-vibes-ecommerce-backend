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

    public function updateOrderItems($userId)
    {
        // Get the latest order for the user
        $orderSql = "SELECT * FROM orders WHERE user_id = :userId ORDER BY order_id DESC LIMIT 1";
        $orderParams = [':userId' => $userId];
        $order = $this->fetch($orderSql, $orderParams);

        if ($order) {
            $orderId = $order['order_id'];
            $cart = $this->userCart->getUserCart($userId);

            if (!$cart) {
                return false;
            }

            $totalAmount = 0;

            // Fetch all current order items
            $orderItemsSql = 'SELECT * FROM order_items WHERE order_id = :orderId';
            $orderItemsParams = [':orderId' => $orderId];
            $orderItems = $this->fetchAll($orderItemsSql, $orderItemsParams);

            // Create an array to track product IDs in the cart
            $cartProductIds = [];

            // Loop through the cart to update or insert items
            foreach ($cart as $item) {
                $productId = $item['productID'];
                $quantity = $item['quantity'];
                $totalPrice = $item['total'];
                $cartProductIds[] = $productId; // Track this product ID from the cart

                // Check if the item already exists in the order_items table
                $checkItemSql = 'SELECT * FROM order_items WHERE order_id = :orderId AND product_id = :productId';
                $checkItemParams = [
                    ':orderId' => $orderId,
                    ':productId' => $productId,
                ];
                $existingItem = $this->fetch($checkItemSql, $checkItemParams);

                if ($existingItem) {
                    // If item exists, update it
                    $sql = 'UPDATE order_items SET quantity = :quantity, price = :price WHERE order_id = :orderId AND product_id = :productId';
                    $params = [
                        ':orderId' => $orderId,
                        ':productId' => $productId,
                        ':quantity' => $quantity,
                        ':price' => $totalPrice
                    ];
                } else {
                    // If item doesn't exist, insert it
                    $sql = 'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:orderId, :productId, :quantity, :price)';
                    $params = [
                        ':orderId' => $orderId,
                        ':productId' => $productId,
                        ':quantity' => $quantity,
                        ':price' => $totalPrice
                    ];
                }

                $this->execute($sql, $params);

                // Add to the total amount
                $totalAmount += $totalPrice;
            }

            // Remove items that are in the order but no longer in the cart
            foreach ($orderItems as $orderItem) {
                if (!in_array($orderItem['product_id'], $cartProductIds)) {
                    // If the product ID is not in the cart, remove it from order_items
                    $deleteItemSql = 'DELETE FROM order_items WHERE order_id = :orderId AND product_id = :productId';
                    $deleteItemParams = [
                        ':orderId' => $orderId,
                        ':productId' => $orderItem['product_id']
                    ];
                    $this->execute($deleteItemSql, $deleteItemParams);
                }
            }

            // Update the total amount in the orders table
            $updateOrderSql = 'UPDATE orders SET total_amount = :totalAmount WHERE order_id = :orderId';
            $updateOrderParams = [
                ':totalAmount' => $totalAmount,
                ':orderId' => $orderId
            ];
            $this->execute($updateOrderSql, $updateOrderParams);
        }

        return true;
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

    public function updateOrderStatus($orderId)
    {
        $sql = 'UPDATE orders SET status = :status WHERE order_id = :orderId';
        $params = [
            ':status' => 'completed',
            ':orderId' => $orderId
        ];
        $this->execute($sql, $params);
    }
}
