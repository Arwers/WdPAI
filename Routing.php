<?php

require_once "src/controllers/DefaultController.php";

class Routing {
    public static $routes = []; // Stores URLs and associated controllers
    
    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
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
