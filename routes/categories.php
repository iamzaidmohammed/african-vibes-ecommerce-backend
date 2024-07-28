<?php
require_once '../controllers/CategoryController.php';

$categoriesController = new CategoriesController();

$categories = $categoriesController->getCategories();

echo json_encode($categories);
