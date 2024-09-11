<?php

namespace app\Models;

use app\Config\Dbh;

class Like extends Dbh
{


    public function createLike($userId, $productId)
    {
        $checkSql = "SELECT * FROM product_likes WHERE user_id = :userId AND product_id = :productId";
        $checkParams = [
            ':userId' => $userId,
            ':productId' => $productId
        ];

        $checkResult = $this->fetch($checkSql, $checkParams);
        if ($checkResult) {
            return false;
        }

        $sql = "INSERT INTO product_likes (user_id, product_id) VALUES (:userId, :productId)";
        $params = [
            ':userId' => $userId,
            ':productId' => $productId
        ];
        return $this->execute($sql, $params);
    }

    public function deleteLike($userId, $productId)
    {
        $sql = "DELETE FROM product_likes WHERE user_id = :userId AND product_id = :productId";
        $params = [
            ':userId' => $userId,
            ':productId' => $productId
        ];
        return $this->execute($sql, $params);
    }

    public function getLikedProducts($userId)
    {
        $sql = "SELECT * FROM product_likes WHERE user_id = :userId";
        $params = [
            ':userId' => $userId
        ];
        return $this->fetchAll($sql, $params);
    }
}
