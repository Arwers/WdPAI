<?php
namespace repository;

require_once __DIR__ . '/Repository.php';

class ExpenseRepository extends Repository
{
    public function addExpense($userId, $date, $name, $type, $price): bool
    {
        $sql = "INSERT INTO expenses (id_user, id_type, date, name, price)
                VALUES (
                    :user_id,
                    (SELECT id_type FROM types WHERE LOWER(type_name) = LOWER(:type) LIMIT 1),
                    :date,
                    :name,
                    :price
                )";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }
}
