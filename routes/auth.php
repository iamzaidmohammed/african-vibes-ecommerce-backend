<?php
require_once '../controllers/AuthController.php';

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
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['password']) || !isset($input['confirmPassword'])) {
            http_response_code(400);
            die(json_encode(['status' => 'error', 'message' => 'Name, email, password, or confirm password not specified']));
        }

        $name = htmlspecialchars($input['name']);
        $email = htmlspecialchars($input['email']);
        $password = htmlspecialchars($input['password']);
        $confirmPassword = htmlspecialchars($input['confirmPassword']);



        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errorMessage = "All fields are required.";
            http_response_code(400);
        }

        if (strlen($password) < 6) {
            $errorMessage = "Password must be at least 6 characters.";
            http_response_code(400);
        }

        if ($password !== $confirmPassword) {
            $errorMessage = "Passwords do not match.";
            http_response_code(400);
        }

        if (empty($errorMessage)) {
            $authController->signup($name, $email, $password);
        } else {
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
        }

        break;

    default:
        http_response_code(400);
        die(json_encode(['status' => 'error', 'message' => 'Invalid action specified']));
}
