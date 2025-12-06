<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    private $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }
 
    private static array $users = [
        [
            'email' => 'anna@example.com',
            'password' => '$2y$10$wz2g9JrHYcF8bLGBbDkEXuJQAnl4uO9RV6cWJKcf.6uAEkhFZpU0i', // test123
            'first_name' => 'Anna'
        ],
        [
            'email' => 'bartek@example.com',
            'password' => '$2y$10$fK9rLobZK2C6rJq6B/9I6u6Udaez9CaRu7eC/0zT3pGq5piVDsElW', // haslo456
            'first_name' => 'Bartek'
        ],
        [
            'email' => 'celina@example.com',
            'password' => '$2y$10$Cq1J6YMGzRKR6XzTb3fDF.6sC6CShm8kFgEv7jJdtyWkhC1GuazJa', // qwerty
            'first_name' => 'Celina'
        ],
    ];

    public function login() {
        
        if (!$this->isPost()) {
            return $this->render("login");
        }

        # check if user is in database
        # render dashboard after sucesfull authentication

        var_dump($_POST);
            
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            return $this->render("login", ["messages" =>"fill all fields"]);
        }
        
        # check if user is in database
        # render dashboard after sucesfull authentication
        
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render('login', ['messages' => 'User not found']);
        }
        
        if (!password_verify($password, $user['password'])) {
            return $this->render('login', ['messages' => 'Wrong password']);
        }


        return $this->render("user-page");

        // create user session, cookie, token JWT

        // $url = "http://$_SERVER[HTTP_HOST]";
        // header("Location: {$url}/dashboard");

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

        // TODO CHECK IF EMAIL ALREADY EXISTS
        
        if ($password !== $repeatPassword) {
            return $this->render('register', ['messages' => 'Passwords should be the same!']);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userRepository->createUser(
            $email, $hashedPassword, $userName, $surname
        );

        return $this->render("login", ['messages' => 'User registered successfully, please login!']);
    }
}