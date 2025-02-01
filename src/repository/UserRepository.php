<?php

namespace repository;
use models\User;
use \PDO;

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $sql = "SELECT u.id, u.email, u.password, 
                       ud.name, ud.surname,
                       r.role_name as role
                FROM \"user\" u
                JOIN user_details ud ON u.id_user_details = ud.id
                LEFT JOIN user_role ur ON u.id = ur.id_user
                LEFT JOIN role r ON ur.id_role = r.id_role
                WHERE u.email = :email
                LIMIT 1";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User(
                $result['email'],
                $result['password'],
                $result['name'],
                $result['surname'],
                $result['id'],
                $result['role'] ?? 'client'
            );

            return $user;
        }
        return null;
    }

    public function addUser(User $user): void
    {
        $pdo = $this->database->connect();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
            INSERT INTO public.user_details (name, surname)
            VALUES (:name, :surname)
            RETURNING id
        ");

            $stmt->bindValue(':name', $user->getName(), PDO::PARAM_STR);
            $stmt->bindValue(':surname', $user->getSurname(), PDO::PARAM_STR);
            $stmt->execute();

            $idUserDetails = $stmt->fetchColumn();

            $stmt = $pdo->prepare("
            INSERT INTO public.user (email, password, id_user_details)
            VALUES (:email, :password, :id_user_details)
        ");

            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($user->getPassword(), PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':id_user_details', $idUserDetails, PDO::PARAM_INT);
            $stmt->execute();

            $pdo->commit();

        } catch (\Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function assignRoleByEmail(string $email, string $role): void
    {
        $pdo = $this->database->connect();

        $stmt = $pdo->prepare("SELECT id FROM public.user WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            throw new \Exception("User with email '$email' not found.");
        }

        $roleName = strtolower(trim($role));
        if ($roleName !== 'admin') {
            $roleName = 'client';
        }
        error_log("Assigning role: " . $roleName);

        $stmt = $pdo->prepare("
            SELECT id_role 
            FROM public.role 
            WHERE LOWER(role_name) = LOWER(:role_name)
            LIMIT 1
        ");
        $stmt->bindValue(':role_name', $roleName, PDO::PARAM_STR);
        $stmt->execute();
        $idRole = $stmt->fetchColumn();

        if (!$idRole) {
            throw new \Exception("Role '$roleName' not found in the role table.");
        }
        error_log("Role id found: " . $idRole);

        error_log("Adding role for user id: " . $userId);

        $stmt = $pdo->prepare("
            INSERT INTO public.user_role (id_user, id_role)
            VALUES (:id_user, :id_role)
        ");
        $stmt->bindValue(':id_user', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':id_role', $idRole, PDO::PARAM_INT);
        $stmt->execute();
    }
}