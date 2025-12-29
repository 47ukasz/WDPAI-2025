<?php

require_once __DIR__ . '/AppController.php';

class ItemController extends AppController {
    public function index() {
        $nav = $this->getNavList();

        return $this->render("item", ["logged_in" => $nav["logged_in"], "nav_items" => $nav["nav_items"]]);
    }
}