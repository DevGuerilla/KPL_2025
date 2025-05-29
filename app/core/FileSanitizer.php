<?php

class FileSanitizer
{
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_FILE_SIZE = 5242880; // 5MB
    private const UPLOAD_PATH = 'img/';
    private const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'scr',
        'vbs', 'js', 'jar', 'asp', 'aspx', 'jsp', 'pl', 'py', 'rb', 'sh', 'cgi'
    ];

    public static function validateImage(array $file, string $folder = 'posts'): array
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'filename' => 'default.jpg'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            Logger::warning('File upload error', ['error_code' => $file['error']]);
            return ['success' => false, 'message' => 'File upload failed.'];
        }

        // Validate file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            Logger::warning('File size exceeded', ['size' => $file['size']]);
            return ['success' => false, 'message' => 'File too large. Max 5MB allowed.'];
        }

        // Validate MIME type
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, self::ALLOWED_IMAGE_TYPES)) {
            Logger::security('Invalid file type uploaded', [
                'mime_type' => $mimeType,
                'filename' => $file['name']
            ]);
            return ['success' => false, 'message' => 'Invalid file type. Only images allowed.'];
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            Logger::security('Dangerous file extension detected', [
                'extension' => $extension,
                'filename' => $file['name']
            ]);
            return ['success' => false, 'message' => 'File type not allowed.'];
        }

        // Additional security checks
        if (!self::isValidImage($file['tmp_name'])) {
            return ['success' => false, 'message' => 'Invalid image file.'];
        }

        // Generate secure filename
        $filename = self::generateSecureFilename($extension);
        $uploadPath = self::UPLOAD_PATH . $folder . '/';

        // Ensure upload directory exists
        if (!self::ensureDirectoryExists($uploadPath)) {
            return ['success' => false, 'message' => 'Upload directory error.'];
        }

        $fullPath = $uploadPath . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // Set proper permissions
            chmod($fullPath, 0644);

            Logger::activity('File uploaded successfully', [
                'filename' => $filename,
                'folder' => $folder,
                'size' => $file['size']
            ]);

            return ['success' => true, 'filename' => $filename];
        }

        Logger::error('Failed to move uploaded file', ['target_path' => $fullPath]);
        return ['success' => false, 'message' => 'Failed to save file.'];
    }

    private static function isValidImage(string $filePath): bool
    {
        // Try to create image resource to validate
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            Logger::security('Invalid image file detected', ['file_path' => basename($filePath)]);
            return false;
        }

        // Check if it's a valid image type
        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
        if (!in_array($imageInfo[2], $allowedTypes)) {
            Logger::security('Unsupported image type', ['image_type' => $imageInfo[2]]);
            return false;
        }

        // Additional check: try to load the image
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($filePath);
                break;
            case IMAGETYPE_WEBP:
                $image = @imagecreatefromwebp($filePath);
                break;
            default:
                return false;
        }

        if ($image === false) {
            Logger::security('Corrupted image file detected');
            return false;
        }

        imagedestroy($image);
        return true;
    }

    private static function generateSecureFilename(string $extension): string
    {
        return uniqid('img_', true) . '.' . $extension;
    }

    private static function ensureDirectoryExists(string $path): bool
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                Logger::error('Failed to create upload directory', ['path' => $path]);
                return false;
            }
        }

        if (!is_writable($path)) {
            Logger::error('Upload directory not writable', ['path' => $path]);
            return false;
        }

        return true;
    }

    public static function deleteFile(string $filename, string $folder = 'posts'): bool
    {
        if (empty($filename) || $filename === 'default.jpg') {
            return true;
        }

        $filePath = self::UPLOAD_PATH . $folder . '/' . $filename;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                Logger::activity('File deleted', [
                    'filename' => $filename,
                    'folder' => $folder
                ]);
                return true;
            } else {
                Logger::error('Failed to delete file', ['file_path' => $filePath]);
                return false;
            }
        }

        return true;
    }

    public static function cleanupOldFiles(string $folder = 'posts', int $daysOld = 30): int
    {
        $uploadPath = self::UPLOAD_PATH . $folder . '/';
        $cutoffTime = time() - ($daysOld * 24 * 60 * 60);
        $deletedCount = 0;

        if (!is_dir($uploadPath)) {
            return 0;
        }

        $files = glob($uploadPath . '*');
        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }
        }

        if ($deletedCount > 0) {
            Logger::activity('Cleaned up old files', [
                'folder' => $folder,
                'deleted_count' => $deletedCount,
                'days_old' => $daysOld
            ]);
        }

        return $deletedCount;
    }
}