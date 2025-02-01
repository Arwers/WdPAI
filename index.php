<?php
session_start();

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// Note: Use fully qualified class names for namespaced controllers.
Router::get('index', 'DefaultController');
Router::post('login', 'SecurityController');
Router::get('new_account', 'DefaultController');
Router::post('register', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::get('tracker', 'controllers\\TrackerController'); // Fully qualified name
Router::post('addExpense', 'controllers\\ApiController');

Router::run($path);
