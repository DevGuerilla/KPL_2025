<?php

class Captcha
{
    private static string $sessionKey = 'captcha_data';
    private static int $maxAttempts = 5;
    private static int $cooldownTime = 900; // 15 minutes
    private static int $captchaExpiration = 300; // 5 minutes

    public static function generateCaptcha(): string
    {
        try {
            self::ensureNotBlocked();

            // Generate random math operation
            $operations = ['+', '-', '×'];
            $op = $operations[array_rand($operations)];
            $num1 = random_int(1, 20);
            $num2 = random_int(1, 15);

            // Ensure positive results for subtraction
            if ($op === '-' && $num1 < $num2) {
                [$num1, $num2] = [$num2, $num1];
            }

            $answer = match($op) {
                '+' => $num1 + $num2,
                '-' => $num1 - $num2,
                '×' => $num1 * $num2,
                default => 0
            };

            $question = "$num1 $op $num2";

            // Store in session with security measures
            $_SESSION[self::$sessionKey] = [
                'answer' => password_hash((string)$answer, PASSWORD_DEFAULT),
                'raw_answer' => $answer, // For validation only
                'question_hash' => hash('sha256', $question . session_id()),
                'created_at' => time(),
                'attempts' => $_SESSION[self::$sessionKey]['attempts'] ?? 0,
                'ip' => self::getClientIP()
            ];

            Logger::activity('Math CAPTCHA generated', [
                'question' => self::sanitizeInput($question),
                'ip' => self::getClientIP()
            ]);

            return $question;

        } catch (Exception $e) {
            Logger::error('CAPTCHA generation failed', ['error' => $e->getMessage()]);
            throw new Exception('Failed to generate security verification');
        }
    }

    public static function validateCaptcha(string $userInput): bool
    {
        try {
            $sanitizedInput = self::sanitizeInput($userInput);

            if (!isset($_SESSION[self::$sessionKey])) {
                Logger::warning('CAPTCHA validation: No session data');
                return false;
            }

            $captchaData = $_SESSION[self::$sessionKey];

            // Check expiration
            if (time() - $captchaData['created_at'] > self::$captchaExpiration) {
                Logger::warning('CAPTCHA validation: Expired');
                self::clearCaptcha();
                return false;
            }

            // Check IP consistency
            if ($captchaData['ip'] !== self::getClientIP()) {
                Logger::warning('CAPTCHA validation: IP mismatch', [
                    'original_ip' => $captchaData['ip'],
                    'current_ip' => self::getClientIP()
                ]);
                return false;
            }

            // Validate answer
            $isValid = (int)$sanitizedInput === (int)$captchaData['raw_answer'];

            if (!$isValid) {
                $_SESSION[self::$sessionKey]['attempts']++;
                Logger::warning('CAPTCHA validation failed', [
                    'attempts' => $_SESSION[self::$sessionKey]['attempts'],
                    'user_answer' => $sanitizedInput
                ]);

                if ($_SESSION[self::$sessionKey]['attempts'] >= self::$maxAttempts) {
                    $_SESSION[self::$sessionKey]['blocked_until'] = time() + self::$cooldownTime;
                    Logger::security('CAPTCHA: User blocked due to excessive failures');
                }
            } else {
                Logger::info('CAPTCHA validation successful');
                self::clearCaptcha();
            }

            return $isValid;

        } catch (Exception $e) {
            Logger::error('CAPTCHA validation error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function isBlocked(): bool
    {
        if (!isset($_SESSION[self::$sessionKey]['blocked_until'])) {
            return false;
        }

        if (time() >= $_SESSION[self::$sessionKey]['blocked_until']) {
            unset($_SESSION[self::$sessionKey]['blocked_until']);
            $_SESSION[self::$sessionKey]['attempts'] = 0;
            Logger::info('CAPTCHA block expired, user unblocked');
            return false;
        }

        return true;
    }

    public static function getRemainingCooldown(): int
    {
        return isset($_SESSION[self::$sessionKey]['blocked_until'])
            ? max(0, $_SESSION[self::$sessionKey]['blocked_until'] - time())
            : 0;
    }

    public static function generateImageCaptcha(): void
    {
        $width = 150;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $lineColor = imagecolorallocate($image, 128, 128, 128);

        imagefill($image, 0, 0, $bgColor);

        // Generate simple math problem
        $num1 = rand(1, 20);
        $num2 = rand(1, 15);
        $operation = '+';
        $answer = $num1 + $num2;
        $text = "$num1 $operation $num2 = ?";

        // Store answer in session
        $_SESSION['captcha_answer'] = $answer;

        // Add noise lines
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, $width), rand(0, $height),
                rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add text
        imagestring($image, 5, 30, 15, $text, $textColor);

        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    private static function ensureNotBlocked(): void
    {
        if (self::isBlocked()) {
            $remaining = self::getRemainingCooldown();
            $minutes = ceil($remaining / 60);
            throw new Exception("Too many failed attempts. Please wait {$minutes} minutes.");
        }
    }

    private static function clearCaptcha(): void
    {
        unset($_SESSION[self::$sessionKey]);
    }

    private static function sanitizeInput(string $input): string
    {
        $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', '', trim($input));
        return htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
    }

    private static function getClientIP(): string
    {
        $headers = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}