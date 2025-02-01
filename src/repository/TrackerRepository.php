<?php

namespace repository;

class TrackerRepository extends Repository
{
    public function getExpenses(): array
    {
        // Check that the user is logged in
        if (!isset($_SESSION['user']['id_user'])) {
            return [];
        }
        $userId = $_SESSION['user']['id_user'];

        // SQL query against the view that shows user expenses
        $sql = "SELECT * FROM view_user_expenses WHERE user_id = :user_id ORDER BY date DESC";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Additional methods can be added here (e.g. for adding or validating expenses)
}
