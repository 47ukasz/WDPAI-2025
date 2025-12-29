<?php

require_once 'Repository.php';

class ItemsRepository extends Repository {

    public function getItems(): ?array {
        $query = $this->database->connect()->prepare('SELECT * FROM public.items');
        $query->execute();

        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        // TODO CLOSE DB CONNECTION

        return $items;
    }

    public function createItem(int $user_id, string $title, string $description, float $price, string $phone_number, string $photo_path = ''): void {
        $query = $this->database->connect()->prepare("INSERT INTO items (user_id, title, description, price, phone_number, photo_path) VALUES (?, ?, ?, ?, ?, ?);");
        
        $query->execute([$user_id, $title, $description, $price, $phone_number, $photo_path]);
    }

    public function deleteItem(int $item_id) {
        $query = $this->database->connect()->prepare("DELETE FROM items WHERE id = :item_id");

        $query->bindParam(':item_id', $item_id, PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount() > 0;
    }

    public function updateItem(int $id, int $user_id, string $title, string $description, float $price, string $phone_number, string $photo_path): void {
        $query = $this->database->connect()->prepare("UPDATE items SET title = ?, description = ?, price = ?, phone_number = ?, photo_path = ? WHERE id = ? AND user_id = ?");
        
        $query->execute([$title, $description, $price, $phone_number, $photo_path, $id, $user_id]);
    }

    public function getItemById(int $item_id): ?array {
        $query = $this->database->connect()->prepare('
            SELECT * FROM items WHERE id = :item_id
        ');

        $query->bindParam(':item_id', $item_id, PDO::PARAM_STR);
        $query->execute();

        $item = $query->fetch(PDO::FETCH_ASSOC);

        if ($item == false) {
            return null;
        }

        return $item;
    }

    public function getItemsByUserId(int $user_id): ?array {
        $query = $this->database->connect()->prepare('
            SELECT * FROM items WHERE user_id = :user_id
        ');
        
        $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $query->execute();

        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($items == false) {
            return null;
        }

        return $items;
    }

}