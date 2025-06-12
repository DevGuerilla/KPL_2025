<?php

class InputValidator
{
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_USERNAME_LENGTH = 50;
    private const MAX_NAME_LENGTH = 100;
    private const MAX_EMAIL_LENGTH = 100;

    public function validateLoginInput(array $data): array
    {
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');
        $captchaAnswer = trim($data['captcha_answer'] ?? '');

        // Check required fields
        if (empty($username) || empty($password) || empty($captchaAnswer)) {
            return [
                'valid' => false,
                'message' => 'Semua field wajib diisi!'
            ];
        }

        // Validate username length
        if (strlen($username) > self::MAX_USERNAME_LENGTH) {
            return [
                'valid' => false,
                'message' => 'Username terlalu panjang!'
            ];
        }

        // Sanitize username
        $sanitizedUsername = $this->sanitizeInput($username);
        if ($sanitizedUsername !== $username) {
            Logger::warning('Username contains suspicious characters', [
                'original' => $username,
                'sanitized' => $sanitizedUsername
            ]);
        }

        return [
            'valid' => true,
            'username' => $sanitizedUsername,
            'password' => $password,
            'captcha_answer' => $captchaAnswer
        ];
    }

    public function validateRegistrationInput(array $data): array
    {
        $username = trim($data['username'] ?? '');
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        $captchaAnswer = trim($data['captcha_answer'] ?? '');

        // Check required fields
        if (empty($username) || empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($captchaAnswer)) {
            return [
                'valid' => false,
                'message' => 'Semua field wajib diisi!'
            ];
        }

        // Validate username
        if (!$this->isValidUsername($username)) {
            return [
                'valid' => false,
                'message' => 'Username hanya boleh mengandung huruf, angka, dan underscore!'
            ];
        }

        if (strlen($username) > self::MAX_USERNAME_LENGTH) {
            return [
                'valid' => false,
                'message' => 'Username terlalu panjang!'
            ];
        }

        // Validate name
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            return [
                'valid' => false,
                'message' => 'Nama terlalu panjang!'
            ];
        }

        // Validate email
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Email tidak valid!'
            ];
        }

        if (strlen($sanitizedEmail) > self::MAX_EMAIL_LENGTH) {
            return [
                'valid' => false,
                'message' => 'Email terlalu panjang!'
            ];
        }

        // Validate password
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return [
                'valid' => false,
                'message' => 'Password minimal ' . self::MIN_PASSWORD_LENGTH . ' karakter!'
            ];
        }

        if ($password !== $confirmPassword) {
            return [
                'valid' => false,
                'message' => 'Password tidak cocok!'
            ];
        }

        // Check password strength
        if (!$this->isStrongPassword($password)) {
            return [
                'valid' => false,
                'message' => 'Password harus mengandung huruf besar, huruf kecil, dan angka!'
            ];
        }

        return [
            'valid' => true,
            'username' => $this->sanitizeInput($username),
            'name' => $this->sanitizeInput($name),
            'email' => $sanitizedEmail,
            'password' => $password,
            'captcha_answer' => $captchaAnswer
        ];
    }

    private function isValidUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
    }

    private function isStrongPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password) === 1;
    }

    private function sanitizeInput(string $input, bool $isRichText = false): string
    {
        if ($isRichText) {
            // For rich text content, only remove the most dangerous patterns
            $input = preg_replace([
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/javascript:/i',
                '/on\w+\s*=/i',
                '/data:text\/html/i',
                '/data:image\/svg\+xml/i'
            ], '', $input);
            return trim($input);
        }

        // For regular input, apply strict sanitization
        // Remove control characters
        $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', '', $input);

        // Remove HTML tags and special characters
        $sanitized = strip_tags($sanitized);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove potential XSS patterns
        $sanitized = preg_replace([
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i',
            '/expression\s*\(/i',
            '/data:text\/html/i',
            '/data:image\/svg\+xml/i'
        ], '', $sanitized);

        return trim($sanitized);
    }
}