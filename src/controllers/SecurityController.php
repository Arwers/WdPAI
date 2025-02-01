<?php

use Models\User;
use repository\UserRepository;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once 'AppController.php';

class SecurityController extends AppController
{
    public function login()
    {
        $userRepository = new UserRepository();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $userRepository->getUser($email);

        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist.']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password.']]);
        }

        // Store user info in session including role
        $_SESSION['user'] = [
            'email'   => $user->getEmail(),
            'name'    => $user->getName(),
            'surname' => $user->getSurname(),
            'id_user' => $user->getId(),
            'role'    => $user->getRole()
        ];

        // Check role from user data and redirect accordingly.
        if (strtolower($user->getRole()) === 'admin') {
            header("Location: adminPanel");
            exit;
        } else {
            header("Location: tracker");
            exit;
        }
    }


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('register');
        }

        $email           = $_POST['email'];
        $name            = $_POST['name'];
        $surname         = $_POST['surname'];
        $password        = $_POST['password'];
        $repeatPassword  = $_POST['repeat_password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('register', ['messages' => ['Invalid email format!']]);
        }

        if ($password !== $repeatPassword) {
            return $this->render('register', ['messages' => ['Passwords do not match!']]);
        }

        $userRepository = new UserRepository();
        $existingUser = $userRepository->getUser($email);
        if ($existingUser !== null) {
            return $this->render('register', ['messages' => ['Email is already in use!']]);
        }

        $user = new User($email, $password, $name, $surname);
        $userRepository->addUser($user);

        return $this->render('login', ['messages' => ['Account created successfully. You can now log in!']]);
    }

    public function logout()
    {
        // Clear all session data
        $_SESSION = [];

        // Optionally, destroy the session cookie if one is used
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Render the login view with a logout confirmation message
        return $this->render('login', ['messages' => ['You have been logged out successfully.']]);
    }
}
