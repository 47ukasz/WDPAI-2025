<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/ItemsRepository.php';
require_once __DIR__ . '/../services/ValidationService.php';

class OfferFormController extends AppController {
    private $itemsRepository;

    public function __construct() {
        $this->itemsRepository = new ItemsRepository();
    }

    public function index(?int $id = null) {
        if ($id !== null) {
            $offer = $this->itemsRepository->getItemById($id);

            if ($offer === null) {
                // np. 404
                return $this->render("404");
            }

            return $this->render("offer-form", ["offer" => $offer]);
        }

        // tryb "dodaj"
        return $this->render("offer-form");
    }

    public function addOffer() {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->render("add-offer");
        }

        $errors = [];
        
        $user_id = (int) $_SESSION["user_id"];
        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $price = (float) ($_POST["price"] ?? 0);
        $phone_number = trim($_POST["phone_number"] ?? ""); 

        $photo = $_FILES["photo"] ?? null;
        
        $photo_path = "";
        
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $fileName = time() . '_' . basename($photo['name']);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($photo['tmp_name'], $uploadDir . $fileName);

            $photo_path = '/uploads/' . $fileName;
        } else {
            $photo_path = '/uploads/default_photo.png';
        }

        if (!ValidationService::required($title) || !ValidationService::length($title, 3, 100)) {
            $errors[] = "Nieprawidłowy tytuł.";
        }

        if (!ValidationService::required($description) || !ValidationService::length($description, 3, 500)) {
            $errors[] = "Nieprawidłowy opis.";
        }

        if (!ValidationService::price($price)) {
            $errors[] = "Nieprawidłowa cena.";
        }

        if (!ValidationService::phone($phone_number)) {
            $errors[] = "Nieprawidłowy numer.";
        }

        // TODO VALIDATE VARIABLES 
        
        if (!empty($errors)) {
            return $this->render("add-offer", ["messages" => $errors]);
        } else {
            $this->itemsRepository->createItem($user_id, $title, $description, $price, $phone_number, $photo_path);

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/user-page");
        }
    }

    public function updateOffer() {
        $this->requireLogin();

        if (!$this->isPost()) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/user-page");
        }

        $errors = [];
        
        $user_id = (int) $_SESSION["user_id"];
        $offer_id = (int) $_POST["id"] ?? null;
        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $price = (float) ($_POST["price"] ?? 0);
        $phone_number = trim($_POST["phone_number"] ?? ""); 
        $photo = $_FILES["photo"] ?? null;
        
        if (!$offer_id) {
            return $this->render("user-page");
        }
        
        $existingOffer = $this->itemsRepository->getItemById($offer_id);

        if ($existingOffer === null) {
            return $this->render("user-page");
        }

        $photo_path = $existingOffer["photo_path"] ?? "/uploads/default_photo.png";
        
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $fileName = time() . '_' . basename($photo['name']);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($photo['tmp_name'], $uploadDir . $fileName);

            $photo_path = '/uploads/' . $fileName;
        } 

        if (!ValidationService::required($title) || !ValidationService::length($title, 3, 100)) {
            $errors[] = "Nieprawidłowy tytuł.";
        }

        if (!ValidationService::required($description) || !ValidationService::length($description, 3, 500)) {
            $errors[] = "Nieprawidłowy opis.";
        }

        if (!ValidationService::price($price)) {
            $errors[] = "Nieprawidłowa cena.";
        }

        if (!ValidationService::phone($phone_number)) {
            $errors[] = "Nieprawidłowy numer.";
        }

        // TODO VALIDATE VARIABLES 
        
        if (!empty($errors)) {
            $offerToRender = $existingOffer;
            $offerToRender["id"] = $offer_id;
            $offerToRender["title"] = $title;
            $offerToRender["description"] = $description;
            $offerToRender["price"] = $price;
            $offerToRender["phone_number"] = $phone_number;
            $offerToRender["photo_path"] = $photo_path;
            return $this->render("add-offer", ["messages" => $errors, "offer" => $offerToRender]);
        } else {
            $this->itemsRepository->updateItem($offer_id, $user_id, $title, $description, $price, $phone_number, $photo_path);

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/user-page");
        }
    }
}