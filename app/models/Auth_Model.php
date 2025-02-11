<?php

class Auth_Model
{
    private $db;
    private $table = 'user';

public function __construct() {
    $this->db = new Database;
}

public function register($data) {
    //validasi input

    $validationResult = $this->validateRegistrationData($data);
    if (isset($validationResult['error'])) {
        return $validationResult;
    }
    //cek ada email atau ga
    $this->db->query("Select email FROM $this->table where email = :email");
    $this->db->bind(':email', $data['email']);
    if($this->db->single()) {
        return ['error' => 'Email already exists'];
    }

    //cek username udah ada atau ga
    $this->db->query("Select username FROM $this->table where username = :username");
    $this->db->bind(':username', $data['username']);
    if($this->db->single()) {
        return ['error' => 'Username already taken'];
    }

    //hash password ARGON2ID
    $passwordHash = password_hash($data['password'], PASSWORD_ARGON2ID, [
        'memory_cost' => 2048,
        'time_cost' => 4,
        'threads' => 3
    ]);

    //input user
    $query = "Insert into $this->table  (username, name, email, password) 
    values (:username, :name, :email, :password)";

    $this->db->query($query);
    $this->db->bind(':username', $data['username']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', filter_var($data['email'], FILTER_SANITIZE_EMAIL));
    $this->db->bind(':password', $passwordHash);


    try {
        $this->db->execute();
        return ['success' => true];
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        return ['error' => 'Registration failed. Please try again.'];
    }
}

public function login($data) {
    //validasi input
    if(empty($data['email']) || empty($data['password'])) {
        return ['error' => 'Please fill in all fields'];
    }

    //get user by email
    $this->db->query("Select * FROM $this->table where email = :email");
    $this->db->bind(':email', $data['email']);
    $user = $this->db->single();

    // Verify password
    if (!$user || !password_verify($data['password'], $user['password'])) {
        return ['error' => 'Invalid credentials'];
    }

    // ReHashPassword
    if (password_needs_rehash($user['password'], PASSWORD_ARGON2ID, [
        'memory_cost' => 2048,
        'time_cost' => 4,
        'threads' => 3
    ])) {
        $newHash = password_hash($data['password'], PASSWORD_ARGON2ID, [
            'memory_cost' => 2048,
            'time_cost' => 4,
            'threads' => 3
        ]);

        // Update password di database
        $this->db->query("UPDATE $this->table SET password = :password WHERE email = :email");
        $this->db->bind(':password', $newHash);
        $this->db->bind(':email', $data['email']);
        $this->db->execute();
    }

    // Create session
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    return ['success' => true, 'user' => $user];
}

    private function validateRegistrationData($data) {
        // Check required fields
        if (empty($data['username']) || empty($data['name']) ||
            empty($data['email']) || empty($data['password']) ||
            empty($data['confirm_password'])) {
            return ['error' => 'Please fill in all fields'];
        }

        // Validasi panjang username (minimal 3 karakter)
        if (strlen($data['username']) < 3) {
            return ['error' => 'Username must be at least 3 characters'];
        }

        //validasi email
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Invalid email format'];
        }

        //validasi keamanan password
        if(strlen($data['password']) < 8) {
            return ['error' => 'Password must be at least 8 characters'];
        }

        if (!preg_match('/[A-Z]/', $data['password']) || // Minimal satu huruf besar
            !preg_match('/[a-z]/', $data['password']) || // Minimal satu huruf kecil
            !preg_match('/[0-9]/', $data['password']) || // Minimal satu angka
            !preg_match('/[\W_]/', $data['password'])) { // Minimal satu karakter spesial
            return ['error' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character'];
        }

        // ** Password tidak boleh mengandung username, nama, atau email **
        $lowerPassword = strtolower($data['password']);
        $lowerUsername = strtolower($data['username']);
        $lowerName = strtolower(str_replace(' ', '', $data['name'])); // Hilangkan spasi
        $lowerEmail = strtolower(explode('@', $data['email'])[0]); // Ambil sebelum '@'

        if (strpos($lowerPassword, $lowerUsername) !== false ||
            strpos($lowerPassword, $lowerName) !== false ||
            strpos($lowerPassword, $lowerEmail) !== false) {
            return ['error' => 'Password cannot contain your username, name, or email address'];
        }


        // Validate password match
        if ($data['password'] !== $data['confirm_password']) {
            return ['error' => 'Passwords do not match'];
        }

        return true;


    }

    public function logout() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        return true;
    }
}