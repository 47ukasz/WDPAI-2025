<?php

require_once __DIR__ . '/AppController.php';

class ItemController extends AppController {
    public function index() {

        return $this->render("item");
    }
}