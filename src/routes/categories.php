<?php

use app\Controllers\CategoryController;

require __DIR__ . '/../../vendor/autoload.php';


$categoryController = new CategoryController();

$categories = $categoryController->getCategories();

echo json_encode($categories);
