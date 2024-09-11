<?php

namespace app\Controllers;

use app\Models\Like;

class LikeController
{
    private $likeModel;

    public function __construct()
    {
        $this->likeModel = new Like();
    }

    public function createLike($userId, $productId)
    {
        return $this->likeModel->createLike($userId, $productId);
    }

    public function deleteLike($userId, $productId)
    {
        return $this->likeModel->deleteLike($userId, $productId);
    }

    public function getLikedProducts($userId)
    {
        return $this->likeModel->getLikedProducts($userId);
    }
}
