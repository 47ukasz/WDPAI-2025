<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';

#TODO Controllery -> singleton

# tutaj za pomoca regex rozpoznawac czy ktos w dashboardzie podal id 
class Routing {

    public static $routes = [
        'login'=> ['controller' => 'SecurityController', 'action' => 'login'],
        'register'=> ['controller' => 'SecurityController', 'action' => 'register'],
        'dashboard'=> ['controller' => 'DashboardConroller', 'action' => 'index']
    ];

    public static function run(string $path) {

        switch($path){
            case 'login':
            case 'register':
            case "dashboard":
                $controller = self::$routes[$path]['controller'];
                $action = self::$routes[$path]['action'];

                $controllerObj = new $controller();
                $controllerObj->$action(12);
                break;
            default:
                include "public/views/404.html";
                break;
}
    }
}