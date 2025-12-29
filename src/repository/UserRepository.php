<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {

    public function getUsers(): ?array {
        $db = $this->database->connect();
        $query = $db->prepare('SELECT * FROM users');
        $query->execute();

        $fetchedUsers = $query->fetchAll(PDO::FETCH_ASSOC);

        $db = null;

        $users = [];

        foreach ($fetchedUsers as $fu) {
            $users[] = new User((int) $fu['id'], $fu['firstname'], $fu['lastname'], $fu['email']
        );
    }

    return $users;
    }

    public function createUser(string $email, string $hashedPassword, string $firstName, string $lastName, string $bio = ''): void {
        $db = $this->database->connect();

        try {
            $db->beginTransaction();

            $query = $db->prepare("INSERT INTO users (firstname, lastname, email, password, bio) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$firstName, $lastName, $email, $hashedPassword, $bio]);

            $userId = $db->lastInsertId();

            $roleQuery = $db->prepare("
                SELECT id FROM roles WHERE name = :role
            ");
            
            $roleQuery->execute(['role' => 'USER']);
            $roleId = $roleQuery->fetchColumn();

            $userRoleQuery = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
            
            $userRoleQuery->execute([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getUserByEmail(string $email): ?array {
        $query = $this->database->connect()->prepare('
            SELECT * FROM users u WHERE email = :email
        ');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return $user;
    }

    public function getUserRoleByEmail(string $email): ?string {
        $query = $this->database->connect()->prepare('
            SELECT name
            FROM user_roles_view
            WHERE email = :email
        ');

        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $role = $query->fetch(PDO::FETCH_ASSOC);

        if ($role === false) {
            return null;
        }

        return $role['name'];
    }

    public function deleteUser(int $user_id) {
        $query = $this->database->connect()->prepare("DELETE FROM users WHERE id = :user_id");

        $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount() > 0;
    }
}
