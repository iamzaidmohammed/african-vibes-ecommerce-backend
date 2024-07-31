<?php

namespace ZmDesign\AfricanVibesEcommerceBackend\Models;

use ZmDesign\AfricanVibesEcommerceBackend\Config\Dbh;

class Category extends Dbh
{
    public function createCategory($name)
    {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $params = [
            ':name' => $name
        ];
        return $this->execute($sql, $params);
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM categories";
        return $this->fetchAll($sql);
    }
}
