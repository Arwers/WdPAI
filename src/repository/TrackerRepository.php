<?php

namespace repository;

class TrackerRepository extends Repository
{
    public function getExpenses(): array
    {
        if (!isset($_SESSION['user']['id_user'])) {
            return [];
        }
        $userId = $_SESSION['user']['id_user'];

        $sql = "SELECT * FROM view_user_expenses WHERE user_id = :user_id ORDER BY date DESC";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalExpenses(): float
    {
        if (!isset($_SESSION['user']['id_user'])) {
            return 0;
        }
        $userId = $_SESSION['user']['id_user'];

        $sql = "SELECT COALESCE(SUM(price), 0) as total_expenses 
                FROM expenses 
                WHERE id_user = :user_id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float)$result['total_expenses'];
    }
}
