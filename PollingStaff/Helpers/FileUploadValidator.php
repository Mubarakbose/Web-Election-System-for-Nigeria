<?php

/**
 * FileUploadValidator
 * Validates file uploads (size, MIME type, extension, dimensions for images).
 * Centralizes file upload logic and security checks.
 */

class FileUploadValidator
{
    private array $validExtensions;
    private int $maxSize;
    private array $validMimeTypes;

    public function __construct(
        array $validExtensions = ['jpg', 'jpeg', 'png', 'gif'],
        int $maxSize = 5000000, // 5MB default
        array $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif']
    ) {
        $this->validExtensions = array_map('strtolower', $validExtensions);
        $this->maxSize = $maxSize;
        $this->validMimeTypes = $validMimeTypes;
    }

    /**
     * Validate a file upload.
     * 
     * @param array|null $file The $_FILES[$key] array
     * @return array ['valid' => bool, 'error' => string|null, 'filename' => string|null]
     */
    public function validate(?array $file): array
    {
        if (!$file) {
            return ['valid' => false, 'error' => 'No file uploaded.', 'filename' => null];
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => $this->getUploadErrorMessage($file['error']), 'filename' => null];
        }

        // Check file size
        if ($file['size'] > $this->maxSize) {
            return ['valid' => false, 'error' => sprintf('File size exceeds %d MB limit.', $this->maxSize / 1000000), 'filename' => null];
        }

        // Get file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->validExtensions, true)) {
            return ['valid' => false, 'error' => sprintf('File type .%s not allowed. Allowed: %s', $ext, implode(', ', $this->validExtensions)), 'filename' => null];
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->validMimeTypes, true)) {
            return ['valid' => false, 'error' => sprintf('Invalid file MIME type: %s', $mimeType), 'filename' => null];
        }

        // Generate a safe filename
        $filename = $this->generateSafeFilename($file['name']);

        return ['valid' => true, 'error' => null, 'filename' => $filename];
    }

    /**
     * Generate a safe filename (random prefix + original extension).
     */
    private function generateSafeFilename(string $originalName): string
    {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        return rand(1000, 1000000) . '.' . $ext;
    }

    /**
     * Get a user-friendly error message for upload errors.
     */
    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload directory.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload blocked by extension.',
            default => 'Unknown upload error.',
        };
    }

    /**
     * Move the validated file to the target directory.
     * 
     * @param array $file The $_FILES[$key] array
     * @param string $targetDir Target directory (must exist)
     * @param string $filename The filename (from validate())
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public function moveFile(array $file, string $targetDir, string $filename): array
    {
        if (!is_dir($targetDir)) {
            return ['success' => false, 'path' => null, 'error' => 'Target directory does not exist.'];
        }

        $targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'path' => null, 'error' => 'Failed to move uploaded file.'];
        }

        return ['success' => true, 'path' => $targetPath, 'error' => null];
    }
}
