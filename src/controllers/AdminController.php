<?php

namespace controllers;

use AppController;
use repository\UserRepository;
use models\User;

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../models/User.php';

class AdminController extends AppController
{
    public function adminPanel() {
        $userRepository = new UserRepository();
        $this->render('admin', [
            'messages' => $_SESSION['message'] ?? null
        ]);
        unset($_SESSION['message']);
    }

    public function adminAddUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->adminPanel();
        }

        $email   = $_POST['email'] ?? '';
        $name    = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $password= $_POST['password'] ?? '';
        $role    = $_POST['role'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('admin', ['messages' => ['Invalid email format!']]);
        }

        if (empty($email) || empty($name) || empty($surname) || empty($password) || empty($role)) {
            return $this->render('admin', ['messages' => ['All fields are required!']]);
        }

        $userRepository = new UserRepository();
        $existingUser = $userRepository->getUser($email);
        if ($existingUser !== null) {
            return $this->render('admin', ['messages' => ['Email is already in use!']]);
        }

        $user = new User($email, $password, $name, $surname, 0, $role);

        try {
            $userRepository->addUser($user);
            $userRepository->assignRoleByEmail($email, $role);
            $_SESSION['message'] = "User added successfully.";
        } catch (\Exception $e) {
            $_SESSION['message'] = "Failed to add user: " . $e->getMessage();
        }

        header("Location: adminPanel");
        exit;
    }
}