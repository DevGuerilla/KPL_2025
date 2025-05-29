<?php
session_start();
require_once '../app/init.php';

try {
    // Generate and display captcha image
    Captcha::generateImageCaptcha();
} catch (Exception $e) {
    // Log the error
    Logger::error('CAPTCHA image generation failed', ['error' => $e->getMessage()]);

    // Create fallback simple image
    $width = 150;
    $height = 50;
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 255, 0, 0);

    imagefill($image, 0, 0, $bgColor);

    // Error message
    imagestring($image, 3, 10, 20, 'CAPTCHA ERROR', $textColor);

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}