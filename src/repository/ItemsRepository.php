<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Item.php';

class ItemsRepository extends Repository {

    public function getItems(int $page = 1, int $pageSize = 5, string $title = ""): ?array {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;

        $query = null;

        if (trim($title) === "") {
            $query = $this->database->connect()->prepare('
                SELECT * FROM items
                LIMIT :limit OFFSET :offset
            ');
        } else {
            $query = $this->database->connect()->prepare('
                SELECT * FROM items
                WHERE title ILIKE :title
                LIMIT :limit OFFSET :offset
            ');
            $query->bindValue(':title', '%' . trim($title) . '%', PDO::PARAM_STR);
        }

        $query->bindValue(':limit', $pageSize, PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $query->execute();

        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        // TODO CLOSE DB CONNECTION

        return $items;
    }

    public function getItemsCount(string $title = ""): int {
        $query = "";

        if (trim($title) === "") {
            $query = $this->database->connect()->prepare('SELECT COUNT(*) AS count FROM items');
        } else {
            $query = $this->database->connect()->prepare('SELECT COUNT(*) AS count FROM items WHERE title ILIKE :title');
            $query->bindValue(':title', '%' . trim($title) . '%', PDO::PARAM_STR);
        }

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            return 0;
        }

        return (int) $result["count"];
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

    public function getItemById(int $item_id) {
        $query = $this->database->connect()->prepare('
            SELECT * FROM items WHERE id = :item_id
        ');

        $query->bindParam(':item_id', $item_id, PDO::PARAM_STR);
        $query->execute();

        $fetchedItem = $query->fetch(PDO::FETCH_ASSOC);

        if ($fetchedItem == false) {
            return null;
        }
        
        $userQuery = $this->database->connect()->prepare('
            SELECT firstname, lastname FROM users WHERE id = :user_id
        ');
        
        $user_id = (int) $fetchedItem["user_id"];

        $userQuery->bindParam(":user_id", $user_id, PDO::PARAM_STR);
        $userQuery->execute();
        
        $fetchedUserData = $userQuery->fetch(PDO::FETCH_ASSOC);

        $fullUserName = "";

        if ($fetchedUserData !== null) {
            $fullUserName = $fetchedUserData["firstname"] . " " . $fetchedUserData["lastname"];
        }

        $item = new Item((int) $fetchedItem["id"], $fullUserName, $fetchedItem["title"], $fetchedItem["phone_number"], $fetchedItem["photo_path"], $fetchedItem["description"], $fetchedItem["created_at"], $fetchedItem["price"]);

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
