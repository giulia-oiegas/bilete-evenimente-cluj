<?php

class authService {

    private db_controller $db;

    public function __construct(db_controller $db) {
        $this->db = new db_controller();;
    }

    //Inregistreaza un utilizator nou
    public function registerUser(string $username, string $email, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $defaultRole = 'user';

        $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";

        try {
            // Foloseste metoda 'execute' din db_controller
            $affectedRows = $this->db->execute($query, [$username, $email, $hashedPassword, $defaultRole]);
            return $affectedRows > 0;
        } catch (Exception $e) {
            // In caz de eroare (username/email duplicat)
            return false;
        }
    }

    //Autentifica un utilizator
    public function loginUser(string $usernameOrEmail, string $password): bool {
        $query = "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?";
        // Foloseste metoda 'select' din db_controller
        $result = $this->db->select($query, [$usernameOrEmail, $usernameOrEmail]);

        if (count($result) === 1) {
            $user = new user($result[0]);

            // Verifica parola criptata
            if (password_verify($password, $user->password)) {

                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['user_role'] = $user->role;
                return true;
            }
        }
        return false;
    }

    //Distruge sesiunea si delogheaza utilizatorul
    public static function logout(): void {
        // Distruge toate variabilele de sesiune
        $_SESSION = [];

        // Sterge cookie-ul de sesiune
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    // Verifica daca utilizatorul este logat
    public static function isUserLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
}
