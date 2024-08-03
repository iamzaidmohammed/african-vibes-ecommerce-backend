<?php

namespace ZmDesign\AfricanVibesEcommerceBackend\Models;

use ZmDesign\AfricanVibesEcommerceBackend\Config\Dbh;

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

    public function getAllCarts()
    {
        $sql = "SELECT cart.cart_id, cart.user_id, cart.product_id, cart.quantity, products.product_name, products.price, products.product_image 
                FROM cart
                JOIN products ON cart.product_id = products.product_id
                JOIN users ON cart.user_id = users.user_id";
        return $this->fetchAll($sql);
    }

    public function getSingleCart($id)
    {
        $sql = "SELECT cart.cart_id, users.user_id, products.product_id, cart.quantity, products.product_name, products.price, products.product_image 
                FROM cart
                JOIN ON cart.product_id = products.product_id
                JOIN ON cart.user_id = users.user_id 
                WHERE cart_id = :id";
        $params = [':id' => $id];
        return $this->fetch($sql, $params);
    }

    public function deleteCart($id)
    {
        $sql = "DELETE FROM cart WHERE cart_id = :id";
        $params = [':id' => $id];
        return $this->execute($sql, $params);
    }
}

    

// cart_id, category_id, cart_name, description, cart_image, price, stock_quantity