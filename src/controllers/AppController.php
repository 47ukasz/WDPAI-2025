<?php

class AppController {
    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";

        $nav = $this->getNavList();

        $variables["logged_in"] = $nav["logged_in"] ?? false;
        $variables["nav_items"] = $nav["nav_items"] ?? [];

        if(file_exists($templatePath)){
            extract($variables);
            
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } else {
            ob_start();
            include $templatePath404;
            $output = ob_get_clean();
        }

        echo $output;
    }

    protected function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
            exit();
        }
    }

    protected function requireAdmin() {
        $this->requireLogin();

        $user_role = (string) $_SESSION["user_role"] ?? "NONE";

        if ($user_role !== 'ADMIN') {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/home");
            exit();
        }
    }

    protected function isGet(): bool {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function isDelete(): bool {
        return $_SERVER["REQUEST_METHOD"] === 'DELETE';
    }

    private function getNavList(): ?array {
        $logged_in = false;
        $user_role = "NONE";
        
        if (isset($_SESSION["is_logged_in"])) {
            $logged_in = (bool) $_SESSION["is_logged_in"];
        }

        if (isset($_SESSION["user_role"])) {
            $user_role = (string) $_SESSION["user_role"];
        }

        $nav_items = [[
            "text" => "Lista ogłoszeń",
            "url" => "/home"
        ]];

        if ($logged_in && $user_role !== "NONE") {
            $nav_items[] = [
                "text" => "Dodaj ogłoszenie",
                "url" => "/add-offer"
            ];
        }

        if ($user_role === "ADMIN") {
            $nav_items[] = [
                "text" => "Panel Administratora",
                "url" => "/admin"
            ];
        }

        return ["logged_in" => $logged_in, "nav_items" => $nav_items];
    }
}