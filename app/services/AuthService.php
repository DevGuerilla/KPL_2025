<?php

class AuthService
{
    private User_model $userModel;
    private InputValidator $inputValidator;

    public function __construct(User_model $userModel)
    {
        $this->userModel = $userModel;
        $this->inputValidator = new InputValidator();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function processLogin(array $postData): array
    {
        if (Captcha::isBlocked()) {
            $remaining = Captcha::getRemainingCooldown();
            $minutes = ceil($remaining / 60);
            Logger::warning('Login attempt blocked due to CAPTCHA rate limit.');
            return [
                'success' => false,
                'message' => "Terlalu banyak percobaan. Tunggu {$minutes} menit.",
                'redirect' => BASEURL . '/auth/login'
            ];
        }

        // Validate CSRF token
        if (!$this->validateCSRFToken($postData)) {
            Logger::warning('Login failed - Invalid CSRF token.');
            return [
                'success' => false,
                'message' => 'Sesi tidak valid atau kedaluwarsa. Silakan coba lagi.',
                'redirect' => BASEURL . '/auth/login'
            ];
        }

        // Validate and sanitize input
        $credentials = $this->inputValidator->validateLoginInput($postData);
        if (!$credentials['valid']) {
            Logger::warning('Login failed - Invalid input data.');
            return [
                'success' => false,
                'message' => $credentials['message'],
                'redirect' => BASEURL . '/auth/login'
            ];
        }

        // Validate CAPTCHA
        if (!Captcha::validateCaptcha($credentials['captcha_answer'])) {
            Logger::warning('Login failed - Invalid CAPTCHA.', [
                'username' => $credentials['username']
            ]);
            return [
                'success' => false,
                'message' => 'Verifikasi keamanan gagal!',
                'redirect' => BASEURL . '/auth/login'
            ];
        }

        // Attempt authentication
        Logger::activity('Login attempt', ['username' => $credentials['username']]);
        $user = $this->userModel->getUserByUsername($credentials['username']);

        if ($user && password_verify($credentials['password'], $user['password'])) {
            Logger::info('User logged in successfully.', [
                'user_id' => $user['id_user'],
                'username' => $user['username']
            ]);

            return [
                'success' => true,
                'message' => 'Selamat datang, ' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . '!',
                'redirect' => BASEURL . '/',
                'user' => $user
            ];
        }

        Logger::warning('Login failed - Invalid credentials.', [
            'username' => $credentials['username']
        ]);

        return [
            'success' => false,
            'message' => 'Username atau password salah!',
            'redirect' => BASEURL . '/auth/login'
        ];
    }

    public function processRegistration(array $postData): array
    {
        if (Captcha::isBlocked()) {
            $remaining = Captcha::getRemainingCooldown();
            $minutes = ceil($remaining / 60);
            Logger::warning('Registration attempt blocked due to CAPTCHA rate limit.');
            return [
                'success' => false,
                'message' => "Terlalu banyak percobaan. Tunggu {$minutes} menit.",
                'redirect' => BASEURL . '/auth/register'
            ];
        }

        // Validate CSRF token
        if (!$this->validateCSRFToken($postData)) {
            Logger::warning('Registration failed - Invalid CSRF token.');
            return [
                'success' => false,
                'message' => 'Sesi tidak valid. Coba lagi.',
                'redirect' => BASEURL . '/auth/register'
            ];
        }

        // Validate CAPTCHA first
        if (!isset($postData['captcha_answer']) || !Captcha::validateCaptcha($postData['captcha_answer'])) {
            Logger::warning('Registration failed - Invalid CAPTCHA.');
            return [
                'success' => false,
                'message' => 'Verifikasi keamanan gagal!',
                'redirect' => BASEURL . '/auth/register'
            ];
        }

        // Validate and sanitize input
        $userData = $this->inputValidator->validateRegistrationInput($postData);
        if (!$userData['valid']) {
            return [
                'success' => false,
                'message' => $userData['message'],
                'redirect' => BASEURL . '/auth/register'
            ];
        }

        // Check if username already exists
        if ($this->userModel->getUserByUsername($userData['username'])) {
            Logger::warning('Registration failed - Username already exists.', [
                'username' => $userData['username']
            ]);
            return [
                'success' => false,
                'message' => 'Username sudah digunakan.',
                'redirect' => BASEURL . '/auth/register'
            ];
        }

        // Create user
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        $profilePic = 'https://ui-avatars.com/api/?name=' . urlencode($userData['name']) . '&background=random&color=fff&font-size=0.5';

        $newUser = [
            'username' => $userData['username'],
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $hashedPassword,
            'profile_picture_url' => $profilePic
        ];

        if ($this->userModel->createUser($newUser) > 0) {
            Logger::info('User registered successfully.', ['username' => $userData['username']]);
            return [
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login.',
                'redirect' => BASEURL . '/auth/login'
            ];
        }

        Logger::error('Registration database error.', ['username' => $userData['username']]);
        return [
            'success' => false,
            'message' => 'Registrasi gagal. Coba lagi nanti.',
            'redirect' => BASEURL . '/auth/register'
        ];
    }

    private function validateCSRFToken(array $postData): bool
    {
        return isset($postData['csrf_token']) && Helper::validateCSRFToken($postData['csrf_token']);
    }
}