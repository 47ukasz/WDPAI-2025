<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminController extends AppController {
    private $userRepository;
    private static $instance = null;

    private function __construct() {
        $this->userRepository = UserRepository::getInstance();
    }

    public static function getInstance(): AdminController {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function index() {
        $this->requireAdmin();
        $currentUserId = (int) $_SESSION["user_id"];
        $users = $this->userRepository->getUsers();

        $filteredUsers = [];

        foreach($users as $user) {
            if ($user->getId() !== $currentUserId) {
                $filteredUsers[] = $user;
            }
        }

        return $this->render("admin", ["users"=> $filteredUsers]);
    }

    public function userDelete() {
        $this->requireAdmin();
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

        $userId = (int)($decoded['id'] ?? 0);

        $isDeleted = $this->userRepository->deleteUser($userId);

        http_response_code(200);
        echo json_encode(["deleted" => $isDeleted]);
    }
}