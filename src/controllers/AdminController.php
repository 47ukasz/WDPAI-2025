<?php

require_once __DIR__ . '/AppController.php';

class AdminController extends AppController {
    public function index() {

        return $this->render("admin");
    }
}