<?php

use app\Controllers\AuthController;

require __DIR__ . '/../../vendor/autoload.php';

$authController = new AuthController();

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['action'])) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => 'Action not specified']));
}

$action = $input['action'];

$errorMessage = "";

switch ($action) {
    case 'signin':
        if (!isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            die(json_encode(['status' => 'error', 'message' => 'Email or password not specified']));
        }

        $email = htmlspecialchars($input['email']);
        $password = htmlspecialchars($input['password']);

        if (empty($email) || empty($password)) {
            $errorMessage = "All fields are required.";
            http_response_code(400);
        }

        if (empty($errorMessage)) {
            $authController->signin($email, $password);
        } else {
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
        }

        break;

    case 'signup':
        if (!isset($input['firstName']) || !isset($input['lastName']) || !isset($input['username']) || !isset($input['email']) || !isset($input['password']) || !isset($input['confirmPassword'])) {
            http_response_code(400);
            die(json_encode(['status' => 'error', 'message' => 'All fields are required.']));
        }

        $firstName = htmlspecialchars($input['firstName']);
        $lastName = htmlspecialchars($input['lastName']);
        $username = htmlspecialchars($input['username']);
        $email = htmlspecialchars($input['email']);
        $password = htmlspecialchars($input['password']);
        $confirmPassword = htmlspecialchars($input['confirmPassword']);

        if (strlen($password) < 6) {
            $errorMessage = "Password must be at least 6 characters long.";
            http_response_code(400);
        }

        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errorMessage = "All fields are required.";
            http_response_code(400);
        }

        if ($password !== $confirmPassword) {
            $errorMessage = "Passwords do not match.";
            http_response_code(400);
        }

        if (empty($errorMessage)) {
            $authController->signup($firstName, $lastName, $username, $email, $password);
        } else {
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
        }

        break;

    default:
        http_response_code(400);
        die(json_encode(['status' => 'error', 'message' => 'Invalid action specified']));
}
