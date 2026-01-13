<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/ItemsRepository.php';

class HomeController extends AppController {
    private $itemsRepository;

    public function __construct() {
        $this->itemsRepository = new ItemsRepository();
    }
    
    public function index() {
        $nav = $this->getNavList();

        return $this->render("home", $nav);
    }

    public function search() {
        header('Content-Type: application/json');
        
        if (!$this->isGet()) {
            http_response_code(405);
            echo json_encode(["status" => "405", "message" => "Method not allowed!"]);
            return;
        }

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pageSize = isset($_GET['pageSize']) ? (int) $_GET['pageSize'] : 5;
        $title = isset($_GET['title']) ? (string) $_GET['title'] : "";
        
        http_response_code(200);

        $items = $this->itemsRepository->getItems($page, $pageSize, $title);
        $total = $this->itemsRepository->getItemsCount($title);

        echo json_encode(["items" => $items, "total" => $total, "page" => $page, "pageSize" => $pageSize]);
    }
}
