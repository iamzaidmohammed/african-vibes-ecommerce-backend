<?php

namespace app\Controllers;

use app\Models\User;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function signup($firstName, $lastName, $username, $email, $password)
    {
        $userEmail = $this->userModel->getUserByEmail($email);
        $user = $this->userModel->getUserByUsername($username);

        if (!$userEmail) {
            if (!$user) {
                if ($this->userModel->createUser($firstName, $lastName, $username, $email, $password)) {
                    http_response_code(201);
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Account creation failed']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'User with email already exists.']);
        }
    }

    public function signin($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Start session and set user data
            session_start();
            $userInfo = [
                'id' => $user['user_id'],
                'firstName' => $user['first_name'],
                'lastName' => $user['last_name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ];
            $_SESSION['user'] = $userInfo;
            http_response_code(200); // OK
            echo json_encode(['status' => 'success', 'user' => $userInfo]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }

    public function updateUserDetails($id, $firstName, $lastName, $email, $phone)
    {
        if ($this->userModel->updateUserDetails($id, $firstName, $lastName, $email, $phone)) {
            http_response_code(200); // OK
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user details']);
        }
    }
}
