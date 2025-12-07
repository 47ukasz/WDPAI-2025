<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/AddOfferController.php';
require_once 'src/controllers/UserPageController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/HomeController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/ItemController.php';

#TODO Controllery -> singleton

# tutaj za pomoca regex rozpoznawac czy ktos w dashboardzie podal id 
class Routing {

    public static $routes = [
        'login'=> ['controller' => 'SecurityController', 'action' => 'login'],
        'dashboard'=> ['controller' => 'DashboardController', 'action' => 'index'],
        'add-offer'=> ['controller' => 'AddOfferController', 'action' => 'index'],
        'user-page'=> ['controller' => 'UserPageController', 'action' => 'index'],
        'register'=> ['controller' => 'SecurityController', 'action' => 'register'],
        'home'=> ['controller' => 'HomeController', 'action' => 'index'],
        'admin'=> ['controller' => 'AdminController', 'action' => 'index'],
        'item'=> ['controller' => 'ItemController', 'action' => 'index']
    ];

    // public static function run(string $path) {
    //     switch($path){
    //         case 'login':
    //         case 'register':
    //         case 'add-offer':
    //         case 'user-page':
    //         case 'dashboard':
    //             $controller = self::$routes[$path]['controller'];
    //             $action = self::$routes[$path]['action'];

    //             $controllerObj = new $controller();
    //             $controllerObj->$action();
    //             break;
    //         default:
    //             include "public/views/404.html";
    //             break;
    //     }
    // }

    public static function run(string $path) {
        if (!isset(self::$routes[$path])) {
            include "public/views/404.html";
            return;
        }

        $controllerName = self::$routes[$path]['controller'];
        $action = self::$routes[$path]['action'];

        $controller = new $controllerName();
        $controller->$action(); // tu kiedyś możesz dodać parametry z URL
    }
}