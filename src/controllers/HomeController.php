<?php

require_once __DIR__ . '/AppController.php';

class HomeController extends AppController {
    public function index() {

        return $this->render("home");
    }
}