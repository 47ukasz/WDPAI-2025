<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminController extends AppController {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function index() {
        $nav = $this->getNavList();
        $users = $this->userRepository->getUsers();

        return $this->render("admin", ["users"=> $users, "logged_in" => $nav["logged_in"], "nav_items" => $nav["nav_items"]]);
    }
}