<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {
    private static $instance = null;
    private $connection;

    private function __construct() {
        parent::__construct();

        $this->connection = $this->database->connect();
    }

    public function __destruct() {
        $this->connection = null;
    }

    public static function getInstance(): UserRepository {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getUsers(): ?array {
        $query = $this->connection->prepare('SELECT * FROM users');
        $query->execute();

        $fetchedUsers = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];

        foreach ($fetchedUsers as $fu) {
            $users[] = new User((int) $fu['id'], $fu['firstname'], $fu['lastname'], $fu['email']);
        }

        return $users;
    }

    public function createUser(string $email, string $hashedPassword, string $firstName, string $lastName, string $bio = ''): void {

        try {
            $this->connection->beginTransaction();

            $query = $this->connection->prepare("INSERT INTO users (firstname, lastname, email, password, bio) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$firstName, $lastName, $email, $hashedPassword, $bio]);

            $userId = $this->connection->lastInsertId();

            $roleQuery = $this->connection->prepare("
                SELECT id FROM roles WHERE name = :role
            ");
            
            $roleQuery->execute(['role' => 'USER']);
            $roleId = $roleQuery->fetchColumn();

            $userRoleQuery = $this->connection->prepare("INSERT INTO users_roles (user_id, role_id) VALUES (:user_id, :role_id)");
            
            $userRoleQuery->execute([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
        }
    }

    public function getUserByEmail(string $email): ?User {
        $query = $this->connection->prepare('
            SELECT * FROM users u WHERE email = :email
        ');

        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        $fetchedUser = new User((int)$user['id'], $user['firstname'], $user['lastname'], $user['email']);

        return $fetchedUser;
    }

    public function getUserRoleByEmail(string $email): ?string {
        $query = $this->connection->prepare('
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
        try {
            $this->connection->beginTransaction();

            $query = $this->connection->prepare('DELETE FROM items WHERE user_id = :user_id');
            $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $query->execute();

            $query = $this->connection->prepare('DELETE FROM users WHERE id = :user_id');
            $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $query->execute();

            $this->connection->commit();

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            $this->connection->rollBack();
            return false;
        }
    }

    public function getUserPassword(string $email): ?string {
        $query = $this->connection->prepare('
            SELECT password FROM users WHERE email = :email
        ');

        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        return $result['password'];
    }
}
