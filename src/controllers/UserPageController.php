<?php

require_once __DIR__ . '/AppController.php';

class UserPageController extends AppController {
    public function index() {

        return $this->render("user-page");
    }
}