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
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function signin($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Start session and set user data
            session_start();
            $_SESSION['user'] = $user;
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
