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

    public function updateCart($productId, $quantity)
    {
        return $this->cartModel->updateCart($productId, $quantity);
    }

    // public function getAll()
    // {
    //     $cart = $this->cartModel->getAllCarts();

    //     if ($cart) {
    //         $items = [];

    //         foreach ($cart as $item) {
    //             $items[] = [
    //                 'cartID' => $item['cart_id'],
    //                 'userID' => $item['user_id'],
    //                 'productID' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'productName' => $item['product_name'],
    //                 'price' => $item['price'],
    //                 'image' => $item['product_images'],
    //             ];
    //         };
    //         return $items;
    //     } else {
    //         return [];
    //     }
    // }

    public function getUserCart($id)
    {
        $cart = $this->cartModel->getUserCart($id);

        if ($cart) {
            $items = [];

            foreach ($cart as $item) {
                $items[] = [
                    'cartID' => $item['cart_id'],           // Accessing 
                    'productID' => $item['product_id'],     // Accessing 'product_id' directly
                    'quantity' => $item['quantity'],        // Accessing 'quantity' directly
                    'productName' => $item['product_name'], // Accessing 'product_name' directly
                    'price' => $item['price'],              // Accessing 'price' directly
                    'total' => $item['price'] * $item['quantity'],
                    'image' => $item['product_images'],      // Accessing 'product_image' directly
                ];
            };
            return $items;
        } else {
            return [];
        }
    }


    public function removeCart($user_id, $product_id)
    {
        return $this->cartModel->deleteUserCart($user_id, $product_id);
    }

    public function emptyCart($orderId)
    {
        return $this->cartModel->clearCart($orderId);
    }
}
