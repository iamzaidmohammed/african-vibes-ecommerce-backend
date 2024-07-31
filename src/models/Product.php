<?php

namespace ZmDesign\AfricanVibesEcommerceBackend\Models;

use ZmDesign\AfricanVibesEcommerceBackend\Config\Dbh;

class Product extends Dbh
{
    public function createProduct($categoryId, $name, $description, $image, $price, $stock)
    {
        $sql = "INSERT INTO products (category_id, product_name, description, product_image, price, stock_quantity) VALUES (:categoryId, :name, :description, :image, :price, :stock)";
        $params = [
            ':categoryId' => $categoryId,
            ':name' => $name,
            ':description' => $description,
            ':image' => $image,
            ':price' => $price,
            ':stock' => $stock
        ];
        return $this->execute($sql, $params);
    }

    public function getAllProducts()
    {
        $sql = "SELECT product_id, category_id, product_name, description, product_image, price, stock_quantity FROM products";
        return $this->fetchAll($sql);
    }

    public function getSingleProduct($id)
    {
        $sql = "SELECT product_id, category_id, product_name, description, product_image, price, stock_quantity FROM products WHERE product_id = :id";
        $params = [':id' => $id];
        return $this->fetch($sql, $params);
    }
}

    

// product_id, category_id, product_name, description, product_image, price, stock_quantity