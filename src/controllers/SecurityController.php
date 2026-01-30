<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../services/ValidationService.php';

class SecurityController extends AppController {
    private $userRepository;
    private static $instance = null;

    private function __construct(){
        $this->userRepository = UserRepository::getInstance();
    }

    public static function getInstance(): SecurityController {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function login() {
        if (!$this->isPost()) {
            if (!isset($_SESSION['csrf'])) {
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            }

            return $this->render("login");
        }

        if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            return $this->render("404");
        }

        $email = $_POST["email"] ?? "";
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            return $this->render("login", ["messages" => "Uzupełnij wszystkie pola."]);
        }
        
        if (!ValidationService::isValidEmail($email)) {
            return $this->render("login", ["messages" => "Niepoprawny adres email."]);
        }

        $user = $this->userRepository->getUserByEmail($email);
        $hashedPassword = $this->userRepository->getUserPassword($email);

        if (!$user) {
            return $this->render('login', ['messages' => 'Email lub hasło niepoprawne.']);
        }
        
        if (!$hashedPassword || !password_verify($password, $hashedPassword)) {
            return $this->render('login', ['messages' => 'Email lub hasło niepoprawne.']);
        }

        $user_role = $this->userRepository->getUserRoleByEmail($email);

        session_regenerate_id(true); 

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_firstname'] = $user->getFirstName();
        $_SESSION['user_role'] = $user_role ?? null;

        $_SESSION['is_logged_in'] = true;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/user-page");
    }

    public function register() {
        if (!$this->isPost()) {
            if (!isset($_SESSION['csrf'])) {
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            }

            return $this->render("register");
        }

        if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            return $this->render("404");
        }
        
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $repeatPassword = $_POST["repeatPassword"] ?? "";
        $userName = $_POST["userName"] ?? "";
        $surname = $_POST["surname"] ?? "";

        if (!ValidationService::isValidEmail($email)) {
            return $this->render("register", ["messages" => "Niepoprawny adres email."]);
        }

        $userExists = $this->userRepository->getUserByEmail($email);

        if ($userExists !== NULL) {
            return $this->render('register', ['messages' => 'Email lub hasło niepoprawne.']);
        }

        if (!ValidationService::required($userName) || !ValidationService::length($userName, 2, 20)) {
            return $this->render('register', ['messages' => 'Imię użytkownika musi się składać z 2 znaków']);
        }
        
        if (!ValidationService::required($surname) || !ValidationService::length($surname, 2, 20)) {
            return $this->render('register', ['messages' => 'Nazwisko użytkownika musi się składać z 2 znaków']);
        }

        if (!ValidationService::length($password, 10, 100)) {
            return $this->render('register', ['messages' => 'Hasło musi składać się z minimum 10 znaków']);
        }

        if ($password !== $repeatPassword) {
            return $this->render('register', ['messages' => 'Podane hasła do siebie nie pasują']);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userRepository->createUser(
            $email, $hashedPassword, $userName, $surname
        );

        return $this->render("login", ['messages' => 'Utworzono nowe konto, zaloguj się!']);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_unset();
        session_destroy();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}