<?php

require_once 'AppController.php';

class AddOfferController extends AppController {
    public function index() {

        return $this->render("add-offer");
    }
}