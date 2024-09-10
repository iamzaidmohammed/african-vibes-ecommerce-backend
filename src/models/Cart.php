<?php

namespace app\Models;

use app\Config\Dbh;

class Cart extends Dbh
{
    // Create a new cart for a user if it doesn't exist, and add an item to the cart
    public function createCart($userId, $productId, $quantity)
    {
        // Check if the cart already exists for the user
        $cartSql = "SELECT cart_id FROM cart WHERE user_id = :userId";
        $cartParams = [':userId' => $userId];
        $cartId = $this->fetch($cartSql, $cartParams)['cart_id'] ?? null;

        // If the cart doesn't exist, create a new one
        if (!$cartId) {
            $createCartSql = "INSERT INTO cart (user_id) VALUES (:userId)";
            $this->execute($createCartSql, $cartParams);
            $cartId = $this->lastInsertId();
        }

        // If the product is not in the cart, add it as a new item
        $addItemSql = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cartId, :productId, :quantity)";
        $addItemParams = [':cartId' => $cartId, ':productId' => $productId, ':quantity' => $quantity];
        return $this->execute($addItemSql, $addItemParams);
    }


    // Update the quantity of a product in the cart
    public function updateCart($productId, $quantity)
    {
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE product_id = :productID";
        $params = [
            ':quantity' => $quantity,
            ':productID' => $productId,
        ];

        return $this->execute($sql, $params);
    }

    // Get all carts (with their items) - admin view
    // public function getAllCarts()
    // {
    //     $sql = "SELECT cart.cart_id, cart.user_id, 
    //                    cart_items.product_id, cart_items.quantity,
    //                    products.product_name, products.price,
    //                    GROUP_CONCAT(product_images.product_image ORDER BY product_images.image_id SEPARATOR ',') AS product_images
    //             FROM cart
    //             JOIN cart_items ON cart.cart_id = cart_items.cart_id
    //             JOIN products ON cart_items.product_id = products.product_id
    //             LEFT JOIN product_images ON products.product_id = product_images.product_id
    //             GROUP BY cart.cart_id, cart_items.product_id";
    //     return $this->fetchAll($sql);
    // }

    // Get a single user's cart with items
    public function getUserCart($id)
    {
        $sql = "SELECT cart.cart_id, 
                       products.product_id, 
                       cart_items.quantity, 
                       products.product_name, 
                       products.price, 
                       GROUP_CONCAT(product_images.product_image ORDER BY product_images.image_id SEPARATOR ',') AS product_images 
                FROM cart
                JOIN cart_items ON cart.cart_id = cart_items.cart_id
                JOIN products ON cart_items.product_id = products.product_id
                LEFT JOIN product_images ON products.product_id = product_images.product_id 
                WHERE cart.user_id = :userId
                GROUP BY products.product_id";
        $params = [':userId' => $id];
        return $this->fetchAll($sql, $params);
    }

    // Delete an item from the cart
    public function deleteCartItem($cartItemId)
    {
        $sql = "DELETE FROM cart_items WHERE cart_item_id = :cartItemId";
        $params = [':cartItemId' => $cartItemId];
        return $this->execute($sql, $params);
    }

    // Delete all items in the cart (optional)
    public function deleteUserCart($userId, $productId)
    {
        $sql = "DELETE FROM cart_items WHERE product_id = :productId AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = :userId)";
        $params = [':productId' => $productId, ':userId' => $userId];
        return $this->execute($sql, $params);
    }

    public function clearCart($cartId)
    {
        $deleteCart = "DELETE FROM cart WHERE cart_id = :cartId";
        $deletedCart = $this->execute($deleteCart, [':cartId' => $cartId]);

        $deleteCartItems = "DELETE FROM cart_items WHERE cart_id = :cartId";
        $deletedCartItems = $this->execute($deleteCartItems, [':cartId' => $cartId]);

        return $deletedCart && $deletedCartItems;
    }
}
