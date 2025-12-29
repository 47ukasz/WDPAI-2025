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
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            http_response_code(405);
            echo json_encode(["status" => "405", "message" => "Method not allowed!"]);
            return;
        }

        if ($contentType !== "application/json") {
            http_response_code(415);
            echo json_encode(["status" => "415", "message" => "Content type not allowed!"]);
            return;
        }
        
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        http_response_code(200);
        $items = $this->itemsRepository->getItems();
        echo json_encode($items);
    }
}