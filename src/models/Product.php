<?php

namespace app\Models;

use app\Config\Dbh;

class Product extends Dbh
{
    public function createProduct($artisanID, $categoryId, $name, $description, $image, $price, $stock, $careInstructions)
    {
        $sql = "INSERT INTO products (artisan_id, category_id, product_name, product_description, product_image, price, stock_quantity, care_instructions) VALUES (?,?,?,?,?,?,?,?)";
        $params = [
            $artisanID,
            $categoryId,
            $name,
            $description,
            $image,
            $price,
            $stock,
            $careInstructions
        ];
        return $this->execute($sql, $params);
    }

    public function getAllProducts()
    {
        $sql = "SELECT 
                p.product_id, 
                p.category_id, 
                p.product_name, 
                p.price, 
                p.stock_quantity, 
                GROUP_CONCAT(pi.product_image ORDER BY pi.image_id SEPARATOR ',') AS product_images
                FROM products p
                LEFT JOIN product_images pi ON p.product_id = pi.product_id
                GROUP BY p.product_id";
        return $this->fetchAll($sql);
    }

    public function getSingleProduct($id)
    {
        $sql = "SELECT 
                p.product_id, 
                p.artisan_id, 
                p.category_id, 
                p.product_name, 
                p.product_description,
                p.product_code,
                p.price, 
                p.stock_quantity, 
                p.care_instructions,
                GROUP_CONCAT(pi.product_image ORDER BY pi.image_id SEPARATOR ',') AS product_images
                FROM products p
                LEFT JOIN product_images pi ON p.product_id = pi.product_id
                WHERE p.product_id = :id
                GROUP BY p.product_id";
        $params = [':id' => $id];
        return $this->fetch($sql, $params);
    }
}
