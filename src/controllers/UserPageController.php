<?php

require_once 'AppController.php';

class UserPageController extends AppController {
    public function index() {

        return $this->render("user-page");
    }
}