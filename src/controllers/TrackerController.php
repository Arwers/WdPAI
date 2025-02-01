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
        // Removed parent::__construct();
        $this->trackerRepository = new TrackerRepository();
    }

    public function tracker() {
        $expenses = $this->trackerRepository->getExpenses();
        $this->render('tracker', ['expenses' => $expenses]);
    }
}
