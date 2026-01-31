<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/ItemsRepository.php';

class ItemController extends AppController {
    private $itemsRepository;
    private static $instance = null;

    private function __construct() {
        $this->itemsRepository = ItemsRepository::getInstance();
    }

    public static function getInstance(): ItemController {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function index(?int $id = null) {
        if ($id === null) {
            http_response_code(404);
            return $this->render("404", [
                "error_code" => 404,
                "error_message" => "Nie znaleziono przedmiotu o podanym ID."
            ]);
        }

        $item = $this->itemsRepository->getItemById($id);
    
        if ($item === null) {
            http_response_code(404);
            return $this->render("404", [
                "error_code" => 404,
                "error_message" => "Nie znaleziono przedmiotu o podanym ID."
            ]);
        }

        return $this->render("item", ["item" => $item]);
    }
}