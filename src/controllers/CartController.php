<?php

namespace app\Controllers;

use app\Models\Cart;

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    public function addCart($userId, $productId, $quantity)
    {
        return $this->cartModel->createCart($userId, $productId, $quantity);
    }

    public function updateCart($userId, $productId, $quantity)
    {
        return $this->cartModel->updataCart($userId, $productId, $quantity);
    }

    public function getAll()
    {
        $cart = $this->cartModel->getAllCarts();

        if ($cart) {
            $items = [];

            foreach ($cart as $item) {
                $items[] = [
                    'cartID' => $item['cart_id'],
                    'userID' => $item['user_id'],
                    'productID' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'productName' => $item['product_name'],
                    'price' => $item['price'],
                    // 'total' => $item['price'] * $item['quantity'],       
                    'image' => $item['product_image'],
                ];
            };
            return $items;
        } else {
            return [];
        }
    }

    public function getUserCart($id)
    {
        $cart = $this->cartModel->getUserCart($id);

        if ($cart) {
            $items = [];

            foreach ($cart as $item) {
                $items[] = [
                    'cartID' => $item['cart_id'],           // Accessing 'cart_id' directly
                    'userID' => $item['user_id'],           // Accessing 'user_id' directly
                    'productID' => $item['product_id'],     // Accessing 'product_id' directly
                    'quantity' => $item['quantity'],        // Accessing 'quantity' directly
                    'productName' => $item['product_name'], // Accessing 'product_name' directly
                    'price' => $item['price'],              // Accessing 'price' directly
                    'total' => $item['price'] * $item['quantity'],
                    'image' => $item['product_image'],      // Accessing 'product_image' directly
                ];
            };
            return $items;
        } else {
            return [];
        }
    }


    public function removeCart($id)
    {
        return $this->cartModel->deleteCart($id);
    }
}
