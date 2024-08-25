<?php

namespace app\Models;

use app\Config\Dbh;

class Category extends Dbh
{
    public function createCategory($name)
    {
        $sql = "INSERT INTO categories (category_name) VALUES (:name)";
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
