<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    public function index() {
        $this->render('login');
    }
    public function new_account() {
        $this->render('register');
    }

    public function tracker() {
        $this->render('tracker');
    }
}