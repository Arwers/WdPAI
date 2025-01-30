<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    public function index() {
        $this->render('login');
    } 

    public function tracker() {
        $this->render('tracker');
    }
}