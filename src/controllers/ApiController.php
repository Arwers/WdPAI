<?php

namespace controllers;

use AppController;
use repository\ExpenseRepository;
use repository\TrackerRepository;

require_once 'AppController.php';
require_once __DIR__ . '/../repository/ExpenseRepository.php';
require_once __DIR__ . '/../repository/TrackerRepository.php';

class ApiController extends AppController
{
    public function addExpense()
    {
        header('Content-Type: application/json');

        // Get the Content-Type header
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        $rawInput = file_get_contents('php://input');
        error_log("Raw input received: " . $rawInput);

        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode($rawInput, true);
        } else {
            parse_str($rawInput, $data);
        }

        if (!$data || !isset($data['date'], $data['name'], $data['type'], $data['price'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data', 'raw' => $rawInput]);
            exit;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user']['id_user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $userId = $_SESSION['user']['id_user'];

        // Insert expense
        $expenseRepo = new ExpenseRepository();
        $insertResult = $expenseRepo->addExpense($userId, $data['date'], $data['name'], $data['type'], $data['price']);
        if (!$insertResult) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add expense']);
            exit;
        }

        // Retrieve updated expenses and total
        $trackerRepo = new TrackerRepository();
        $expenses = $trackerRepo->getExpenses();
        $totalExpenses = $trackerRepo->getTotalExpenses();

        echo json_encode([
            'success'       => true,
            'message'       => 'Expense added successfully',
            'expenses'      => $expenses,
            'totalExpenses' => $totalExpenses
        ]);
    }
}
