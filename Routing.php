<?php

require_once "src/controllers/DefaultController.php";
require_once "src/controllers/SecurityController.php";
require_once "src/controllers/TrackerController.php";
require_once "src/controllers/ApiController.php";
require_once "src/controllers/AdminController.php";

class Router {
    public static $routes = []; // Stores URLs and associated controllers

    public static function get($url, $view) {
        self::$routes[$url] = $view;
    }

    public static function post($url, $view) {
        self::$routes[$url] = $view;
    }

    public static function run($url) {
        $action = explode("/", $url)[0];

        if (!array_key_exists($action, self::$routes)) {
            die("Wrong URL!");
        }

        $controllerName = self::$routes[$action];
        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            die("Action not found!");
        }

        $controller->$action();
    }
}
