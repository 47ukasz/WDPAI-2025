<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/ItemsRepository.php';

class ItemController extends AppController {
    private $itemsRepository;

    public function __construct() {
        $this->itemsRepository = new ItemsRepository();
    }

    public function index(?int $id = null) {
        if ($id === null) {
            return $this->render("404");
        }

        $item = $this->itemsRepository->getItemById($id);

        if ($item === null) {
            return $this->render("404");
        }

        $nav = $this->getNavList();

        return $this->render("item", ["item" => $item, "logged_in" => $nav["logged_in"], "nav_items" => $nav["nav_items"]]);
    }
}