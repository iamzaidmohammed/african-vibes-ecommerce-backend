<?php

namespace app\Models;

use app\Config\Dbh;

class Cart extends Dbh
{
    public function createCart($userId, $productId, $quantity)
    {

        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        $params = [
            ':userId' => $userId,
            ':productId' => $productId,
            ':quantity' => $quantity
        ];
        return $this->execute($sql, $params);
    }

    public function updataCart($userId, $productId, $quantity)
    {
        $sql = "UPDATE cart SET user_id = :userID, product_id = :product_id, quantity = :quantity 
        WHERE product_id = :product_id";
        $params = [
            ':userID' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
        ];

        return $this->execute($sql, $params);
    }

    public function getAllCarts()
    {
        $sql = "SELECT cart.cart_id, cart.user_id, cart.product_id, cart.quantity,      products.product_name, products.price, products.product_image 
                FROM cart
                JOIN products ON cart.product_id = products.product_id
                JOIN users ON cart.user_id = users.user_id";
        return $this->fetchAll($sql);
    }

    public function getSingleCart($id)
    {
        $sql = "SELECT cart.cart_id, users.user_id, products.product_id, cart.quantity, products.product_name, products.price, products.product_image 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        JOIN users ON cart.user_id = users.user_id 
        WHERE users.user_id = :id";
        $params = [':id' => $id];
        return $this->fetch($sql, $params);
    }

    public function getUserCart($id)
    {
        $sql = "SELECT cart.cart_id, users.user_id, products.product_id, cart.quantity, products.product_name, products.price, products.product_image 
                FROM cart 
                JOIN products ON cart.product_id = products.product_id 
                JOIN users ON cart.user_id = users.user_id 
                WHERE users.user_id = :id";
        $params = [':id' => $id];
        return $this->fetchAll($sql, $params);
    }


    public function deleteCart($id)
    {
        $sql = "DELETE FROM cart WHERE product_id = :id";
        $params = [':id' => $id];
        return $this->execute($sql, $params);
    }
}

    

// cart_id, category_id, cart_name, description, cart_image, price, stock_quantity