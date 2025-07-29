<?php

namespace AnimeSite\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use AnimeSite\Services\FileService;
use Illuminate\Support\Str;

trait HasFiles
{
    /**
     * Get the file service instance
     *
     * @return FileService
     */
    protected function fileService(): FileService
    {
        return app(FileService::class);
    }

    /**
     * Handle file upload for a model attribute
     *
     * @param UploadedFile|string|null $file The uploaded file or file path
     * @param string $directory The directory to store the file in
     * @param string|null $oldFilePath The old file path to delete if exists
     * @return string|null The stored file path or null if no file
     */
    public function handleFileUpload($file, string $directory, ?string $oldFilePath = null): ?string
    {
        // Якщо це вже шлях (string), і не файл — нічого не міняємо
        if (is_string($file) && !$file instanceof UploadedFile && !str_starts_with($file, 'data:image')) {
            return $file;
        }

        // Multipart файл
        if ($file instanceof UploadedFile) {
            return $this->fileService()->storeFileToAzure($file, $directory, $oldFilePath);
        }

        // Base64 зображення
        if (is_string($file) && str_starts_with($file, 'data:image')) {
            return $this->fileService()->storeBase64ImageToAzure($file, $directory, $oldFilePath);
        }

        return null;
    }


    /**
     * Get the full URL for a file path
     *
     * @param string|null $filePath The file path
     * @return string|null The full URL or null if no file
     */
    public function getFileUrl(?string $filePath): ?string
    {
        return $this->fileService()->getFileUrl($filePath);
    }

    /**
     * Delete a file
     *
     * @param string|null $filePath The file path to delete
     * @return bool Whether the file was deleted
     */
    public function deleteFile(?string $filePath): bool
    {
        return $this->fileService()->deleteFile($filePath);
    }

    /**
     * Process an array of files (for JSON fields like pictures)
     *
     * @param array|null $files Array of files or file paths
     * @param string $directory The directory to store files in
     * @param array|null $oldFiles The old files to delete if replaced
     * @return array The processed files
     */
    public function processFilesArray(?array $files, string $directory, ?array $oldFiles = null): array
    {
        if (!$files) {
            return [];
        }

        $processedFiles = [];
        $oldFilesMap = [];

        // Create a map of old files for easier lookup
        if ($oldFiles) {
            foreach ($oldFiles as $oldFile) {
                if (is_string($oldFile)) {
                    $oldFilesMap[basename($oldFile)] = $oldFile;
                }
            }
        }

        foreach ($files as $index => $file) {
            // If it's already a string path and not a new file, keep it
            if (is_string($file) && !$file instanceof UploadedFile && !str_starts_with($file, 'data:image')) {
                $processedFiles[] = $file;
                continue;
            }

            // Find old file to replace if any
            $oldFilePath = null;
            if ($oldFiles && isset($oldFiles[$index])) {
                $oldFilePath = $oldFiles[$index];
            }

            // Store the new file
            $filePath = $this->handleFileUpload($file, $directory, $oldFilePath);
            if ($filePath) {
                $processedFiles[] = $filePath;
            }
        }

        // Delete any old files that weren't replaced
        if ($oldFiles) {
            foreach ($oldFiles as $oldFile) {
                if (is_string($oldFile) && !in_array($oldFile, $processedFiles)) {
                    $this->deleteFile($oldFile);
                }
            }
        }

        return $processedFiles;
    }

    /**
     * Process attachments for Movie model
     *
     * @param array|null $attachments The attachments array
     * @param string $directory The directory to store files in
     * @return array The processed attachments
     */
    public function processAttachments(?array $attachments, string $directory): array
    {
        if (!$attachments) {
            return [];
        }

        return $this->fileService()->processAttachments($attachments, $directory);
    }
    public function storeBase64ImageToAzure(string $base64, string $directory, ?string $oldFile = null): string
    {
        $extension = explode('/', mime_content_type($base64))[1];
        $filename = uniqid('', true) . '.' . $extension;
        $path = "$directory/$filename";
        $content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

        Storage::disk('azure')->put($path, $content);

        // Видалення старого
        if ($oldFile) {
            $this->deleteFromAzure($oldFile);
        }

        return Storage::disk('azure')->url($path);
    }

    public function storeFileToAzure(UploadedFile $file, string $directory, ?string $oldFile = null): string
    {
        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $path = "$directory/$filename";

        Storage::disk('azure')->put($path, file_get_contents($file));

        if ($oldFile) {
            $this->deleteFromAzure($oldFile);
        }

        return Storage::disk('azure')->url($path);
    }
    public function deleteFromAzure(string $url): void
    {
        $path = ltrim(parse_url($url, PHP_URL_PATH), '/');
        Storage::disk('azure')->delete($path);
    }
}
