<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    private $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function login() {
        
        if (!$this->isPost()) {
            return $this->render("login");
        }

        $email = $_POST["email"] ?? "";
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            return $this->render("login", ["messages" =>"Uzupełnij wszystkie pola."]);
        }
        
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render('login', ['messages' => 'Nie znaleziono użytkownika.']);
        }
        
        if (!password_verify($password, $user['password'])) {
            return $this->render('login', ['messages' => 'Nieprawidłowe hasło.']);
        }

        $user_role = $this->userRepository->getUserRoleByEmail($email);

        session_regenerate_id(true); 

        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['user_email'] = $user['email']; 
        $_SESSION['user_firstname'] = $user['firstname'] ?? null;
        $_SESSION['user_role'] = $user_role ?? null;

        $_SESSION['is_logged_in'] = true;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/user-page");
    }

    public function register() {
        if (!$this->isPost()) {
            return $this->render("register");
        }
        
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $repeatPassword = $_POST["repeatPassword"] ?? "";
        $userName = $_POST["userName"] ?? "";
        $surname = $_POST["surname"] ?? "";

        $userExists = $this->userRepository->getUserByEmail($email);

        if ($userExists !== NULL) {
            return $this->render('register', ['messages' => 'Nie można utworzyć konta.']);
        }
        
        if ($password !== $repeatPassword) {
            return $this->render('register', ['messages' => 'Podane hasła do siebie nie pasują']);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userRepository->createUser(
            $email, $hashedPassword, $userName, $surname
        );

        return $this->render("login", ['messages' => 'User registered successfully, please login!']);
    }

    public function logout() {
        // upewniamy się, że sesja jest uruchomiona
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // czyścimy wszystkie dane sesji
        $_SESSION = [];

        // opcjonalnie, kasujemy ciasteczko sesji po stronie przeglądarki
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

        // niszczymy sesję
        session_destroy();
        // przekierowanie np. na ekran logowania
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}