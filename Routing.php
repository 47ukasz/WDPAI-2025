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
        'logout'=> ['controller' => 'SecurityController', 'action' => 'logout'],
        'dashboard'=> ['controller' => 'DashboardController', 'action' => 'index'],
        'add-offer'=> ['controller' => 'AddOfferController', 'action' => 'index'],
        'addOffer'=> ['controller' => 'AddOfferController', 'action' => 'addOffer'],
        'update-offer'=> ['controller' => 'AddOfferController', 'action' => 'index'],
        'updateOffer'=> ['controller' => 'AddOfferController', 'action' => 'updateOffer'],
        'user-page'=> ['controller' => 'UserPageController', 'action' => 'index'],
        'offer-delete'=> ['controller' => 'UserPageController', 'action' => 'deleteOffer'],
        'register'=> ['controller' => 'SecurityController', 'action' => 'register'],
        'home'=> ['controller' => 'HomeController', 'action' => 'index'],
        'admin'=> ['controller' => 'AdminController', 'action' => 'index'],
        'item'=> ['controller' => 'ItemController', 'action' => 'index'],
        'search-cards'=> ['controller' => 'DashboardController', 'action' => 'search'],
        'search-offers'=> ['controller' => 'HomeController', 'action' => 'search'],
    ];

    public static function run(string $path) {
        // trzeba regex dać
        // singleton do repository, bazy danych oraz wlasnie routing
        // IN_ARRAY($path, Routing::$routes)

        if (!preg_match('#^([a-z0-9\-]+)(?:/(\d+))?$#i', $path, $matches)) {
            include "public/views/404.html";
            return;
        }

        $route = $matches[1];
        $id = isset($matches[2]) ? (int)$matches[2] : null;
        
        if (!isset(self::$routes[$route])) {
            include "public/views/404.html";
            return;
        }

        $controllerName = self::$routes[$route]['controller'];
        $action = self::$routes[$route]['action'];

        $controller = new $controllerName();

        if ($id !== null) {
            $controller->$action($id);
        } else {
            $controller->$action();
        }
    }
}