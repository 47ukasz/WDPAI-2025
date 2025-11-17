<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/AddOfferController.php';
require_once 'src/controllers/UserPageController.php';

#TODO Controllery -> singleton

# tutaj za pomoca regex rozpoznawac czy ktos w dashboardzie podal id 
class Routing {

    public static $routes = [
        'login'=> ['controller' => 'SecurityController', 'action' => 'login'],
        'register'=> ['controller' => 'SecurityController', 'action' => 'register'],
        'dashboard'=> ['controller' => 'DashboardController', 'action' => 'index'],
        'add-offer'=> ['controller' => 'AddOfferController', 'action' => 'index'],
        'user-page'=> ['controller' => 'UserPageController', 'action' => 'index']
    ];

    public static function run(string $path) {
        switch($path){
            case 'login':
            case 'register':
            case 'add-offer':
            case 'user-page':
            case 'dashboard':
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