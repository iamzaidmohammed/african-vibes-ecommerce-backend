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
                    'total' => $item['price'] * $item['quantity'],
                    'image' => $item['product_image'],
                ];
            };
            return $items;
        } else {
            return [];
        }

        // return $cart;
    }

    public function getOne($id)
    {
        $cart = $this->cartModel->getSingleCart($id);

        if ($cart) {
            $item = [
                'id' => $cart['cart_id'],
                'userId' => $cart['user_id'],
                'productId' => $cart['product_id'],
                'quantity' => $cart['quantity'],
            ];
            return $item;
        }
    }

    public function removeCart($id)
    {
        return $this->cartModel->deleteCart($id);
    }
}
