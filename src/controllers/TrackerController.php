<?php

namespace controllers;

use AppController;
use repository\TrackerRepository;

require_once __DIR__ . '/../repository/TrackerRepository.php';
require_once 'AppController.php';

class TrackerController extends AppController
{
    private $trackerRepository;

    public function __construct() {
        $this->trackerRepository = new TrackerRepository();
    }

    public function tracker() {

        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header("Location: login");
            exit;
        }

        $expenses = $this->trackerRepository->getExpenses();
        $totalExpenses = $this->trackerRepository->getTotalExpenses();

        $this->render('tracker', [
            'expenses'      => $expenses,
            'totalExpenses' => $totalExpenses
        ]);
    }
}
