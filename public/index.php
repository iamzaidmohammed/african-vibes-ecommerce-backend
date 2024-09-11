<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// echo $_ENV['PAYSTACK_SECRET_KEY'];

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
    case '/orders':
        require $routesPath . '/orders.php';
        break;
    case '/payment':
        require $routesPath . '/payment.php';
        break;
    case '/likes':
        require $routesPath . '/likes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Not Found']);
        break;
}
