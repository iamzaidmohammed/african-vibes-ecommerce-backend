<?php
require_once '../controllers/AuthController.php';

$authController = new AuthController();

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['action'])) {
    die("Action not specified");
}

$action = $input['action'];

switch ($action) {
    case 'signin':
        // Ensure email and password keys exist before using them
        if (!isset($input['email']) || !isset($input['password'])) {
            die("Email or password not specified");
        }

        $email = $input['email'];
        $password = $input['password'];
        $authController->signin($email, $password);

        break;

    case 'signup':
        // Ensure name, email, and password keys exist before using them
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
            die("Name, email, or password not specified");
        }

        $name = $input['name'];
        $email = $input['email'];
        $password = $input['password'];
        $authController->signup($name, $email, $password);

        break;

    default:
        die("Invalid action specified");
}
