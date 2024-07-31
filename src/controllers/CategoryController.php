<?php

namespace ZmDesign\AfricanVibesEcommerceBackend\Controllers;

use ZmDesign\AfricanVibesEcommerceBackend\Models\Category;

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function addCategory()
    {
        // Add logic
    }

    public function getCategories()
    {
        $categories = $this->categoryModel->getCategories();

        if ($categories) {
            $results = [];
            foreach ($categories as $category) {
                $results[] = [
                    'id' => $category['category_id'],
                    'name' => $category['name']
                ];
            }
            return $results;
        }
    }
}
