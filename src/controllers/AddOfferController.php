<?php

require_once __DIR__ . '/AppController.php';

class AddOfferController extends AppController {
    public function index() {

        return $this->render("add-offer");
    }
}