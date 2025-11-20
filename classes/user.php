<?php

// Detinerea si gestionarea datelor ale unui utilizator
class user {
public int $id;
public string $username;
public string $email;
public string $password; // Parola hash
public string $role;


//Constructor pentru a initializa un obiect user
public function __construct(array $data) {
$this->id = $data['id'] ?? 0;
$this->username = $data['username'] ?? '';
$this->email = $data['email'] ?? '';
$this->password = $data['password'] ?? '';
$this->role = $data['role'] ?? 'user';
}


//Verifica daca utilizatorul este admin

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
}
?>
