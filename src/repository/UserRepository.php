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
        $sql = "
        SELECT u.email, u.password, ud.name, ud.surname
        FROM public.user u
        JOIN public.user_details ud ON u.id_user_details = ud.id
        WHERE u.email = :email
    ";

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            // TODO: throw an exception or handle error
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname']
        );
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



}