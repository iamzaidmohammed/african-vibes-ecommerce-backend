<?php

require __DIR__ . '/../vendor/autoload.php';

// Define the base directory
$baseDir = '/african-vibes-ecommnerce-backend/public';

// Set up routing
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = strtok($uri, '?'); // Ignore query parameters

// Remove the base directory from the URI
if (strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}

// Define the path to the routes folder
$routesPath = __DIR__ . '/../src/routes';

switch ($uri) {
    case '/auth':
        require $routesPath . '/auth.php';
        break;
    case '/products':
        require $routesPath . '/products.php';
        break;
    case '/categories':
        require $routesPath . '/categories.php';
        break;
    case '/cart':
        require $routesPath . '/cart.php';
        break;
    case '/shipping':
        require $routesPath . '/shipping.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Not Found']);
        break;
}
