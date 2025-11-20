<?php
/**
 Are rolul de a detine si gestiona datele unui singur utilizator.
*/
class User {
// Proprietatile corespund coloanelor din tabela USERS
public int $id;
public string $username;
public string $email;
public string $password; // Parola hash-uita
public string $role;

/**
Constructor pentru a initializa un obiect User.
*/
public function __construct(array $data) {
$this->id = $data['id'] ?? 0;
$this->username = $data['username'] ?? '';
$this->email = $data['email'] ?? '';
$this->password = $data['password'] ?? '';
$this->role = $data['role'] ?? 'user';
}

    /**
      Metoda ajutatoare pentru a verifica daca utilizatorul este admin.
     */
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
}
?>
