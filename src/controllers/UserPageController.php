<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/ItemsRepository.php';

class UserPageController extends AppController {
    private $itemsRepository;
    private static $instance = null;

    private function __construct() {
        $this->itemsRepository = ItemsRepository::getInstance();
    }

    public static function getInstance(): UserPageController {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function index() {
        $this->requireLogin();
        
        $user_id = (int) $_SESSION["user_id"];
        $items = $this->itemsRepository->getItemsByUserId($user_id);

        return $this->render("user-page", ["items" => $items]);
    }

    public function deleteOffer() {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        header('Content-Type: application/json');
        
        if (!$this->isDelete()) {
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

        $itemId = (int)($decoded['id'] ?? 0);

        $isDeleted = $this->itemsRepository->deleteItem($itemId);

        http_response_code(200);
        echo json_encode(["deleted" => $isDeleted]);
    }
}