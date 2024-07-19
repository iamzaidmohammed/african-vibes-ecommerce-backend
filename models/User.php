<?php
require_once '../config/Dbh.php';

class User extends Dbh
{
    public function createUser($name, $email, $password)
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        return $this->execute($sql, $params);
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $params = [':email' => $email];
        return $this->fetch($sql, $params);
    }
}
