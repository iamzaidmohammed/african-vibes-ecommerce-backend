<?php
require_once '../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function signup($name, $email, $password)
    {
        if ($this->userModel->createUser($name, $email, $password)) {
            http_response_code(201); // Created
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'User creation failed']);
        }
    }

    public function signin($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Start session and set user data
            session_start();
            $_SESSION['user'] = $user;
            http_response_code(200); // OK
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }
}
