<?php

class UploadFile
{
    public static function upload(array $file, string $name, string $folder): string
    {
        // Use the new FileSanitizer for secure file handling
        $result = FileSanitizer::validateImage($file[$name] ?? $file, $folder);

        if (!$result['success']) {
            Logger::error('File upload failed', [
                'error' => $result['message'],
                'folder' => $folder
            ]);

            Flasher::setFlash(false, ['message' => $result['message']]);
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        return $result['filename'];
    }

    // Legacy method for backward compatibility
    public static function uploadLegacy(array $file, string $name, string $folder): string
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5242880; // 5MB

        if (!isset($file[$name]) || $file[$name]['error'] === UPLOAD_ERR_NO_FILE) {
            return 'default.jpg';
        }

        $uploadedFile = $file[$name];

        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            Logger::warning('File upload error', [
                'error_code' => $uploadedFile['error'],
                'file_name' => $uploadedFile['name'] ?? 'unknown'
            ]);

            Flasher::setFlash(false, ['message' => 'File upload failed']);
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        // Validate file extension
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            Logger::security('Invalid file extension', [
                'extension' => $fileExtension,
                'allowed' => implode(', ', $allowedExtensions)
            ]);

            Flasher::setFlash(false, ['message' => 'File extension not allowed']);
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        // Validate file size
        if ($uploadedFile['size'] > $maxSize) {
            Logger::warning('File size exceeded', [
                'size' => $uploadedFile['size'],
                'max_size' => $maxSize
            ]);

            Flasher::setFlash(false, ['message' => 'File too large']);
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        // Generate secure filename
        $newFileName = uniqid('upload_', true) . '.' . $fileExtension;
        $uploadPath = "img/{$folder}/{$newFileName}";

        // Ensure upload directory exists
        $uploadDir = "img/{$folder}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
            chmod($uploadPath, 0644);

            Logger::activity('File uploaded successfully', [
                'original_name' => $uploadedFile['name'],
                'new_name' => $newFileName,
                'folder' => $folder,
                'size' => $uploadedFile['size']
            ]);

            return $newFileName;
        }

        Logger::error('Failed to move uploaded file', [
            'temp_path' => $uploadedFile['tmp_name'],
            'target_path' => $uploadPath
        ]);

        Flasher::setFlash(false, ['message' => 'Failed to save file']);
        header('Location: ' . BASEURL . '/dashboard/createpost');
        exit;
    }

    public static function deleteFile(string $fileName, string $folder): bool
    {
        return FileSanitizer::deleteFile($fileName, $folder);
    }
}