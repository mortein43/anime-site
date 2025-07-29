<?php

namespace AnimeSite\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Store a file and return the path
     *
     * @param UploadedFile|null $file The uploaded file
     * @param string $directory The directory to store the file in
     * @param string|null $oldFilePath The old file path to delete if exists
     * @param bool $public Whether to store in public disk
     * @return string|null The stored file path or null if no file
     */
    public function storeFile(?UploadedFile $file, string $directory, ?string $oldFilePath = null, bool $public = true): ?string
    {
        if (!$file) {
            return null;
        }

        // Delete old file if exists
        if ($oldFilePath) {
            $this->deleteFile($oldFilePath, $public);
        }

        // Generate a unique filename with original extension
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $disk = $public ? 'public' : 'local';
        $path = $file->storeAs($directory, $filename, $disk);

        return $path;
    }
    public function getAzureUrl(string $path): string
    {
        return "https://" . env('AZURE_STORAGE_NAME') . ".blob.core.windows.net/" . env('AZURE_STORAGE_CONTAINER') . "/" . ltrim($path, '/');
    }

    /**
     * Delete a file
     *
     * @param string|null $filePath The file path to delete
     * @param bool $public Whether the file is in public disk
     * @return bool Whether the file was deleted
     */
    public function deleteFile(?string $filePath, bool $public = true): bool
    {
        if (!$filePath) {
            return false;
        }

        $disk = $public ? 'public' : 'local';

        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }

    /**
     * Get the full URL for a file path
     *
     * @param string|null $filePath The file path
     * @param bool $public Whether the file is in public disk
     * @return string|null The full URL or null if no file
     */
    public function getFileUrl(?string $filePath, bool $public = true): ?string
    {
        if (!$filePath) {
            return null;
        }

        if (str_starts_with($filePath, 'http')) {
            return $filePath;
        }

        if ($public) {
            return Storage::url($filePath);
        }

        return Storage::url($filePath);
    }

    /**
     * Store a base64 encoded image and return the path
     *
     * @param string|null $base64String The base64 encoded image
     * @param string $directory The directory to store the file in
     * @param string|null $oldFilePath The old file path to delete if exists
     * @param bool $public Whether to store in public disk
     * @return string|null The stored file path or null if no file
     */
    public function storeBase64Image(?string $base64String, string $directory, ?string $oldFilePath = null, bool $public = true): ?string
    {
        if (!$base64String || !Str::startsWith($base64String, 'data:image')) {
            return null;
        }

        // Delete old file if exists
        if ($oldFilePath) {
            $this->deleteFile($oldFilePath, $public);
        }

        // Extract image data and extension
        $imageData = explode(',', $base64String);
        $imageInfo = explode(';', $imageData[0]);
        $extension = str_replace('data:image/', '', $imageInfo[0]);

        // Generate a unique filename
        $filename = Str::uuid() . '.' . $extension;

        // Decode and store the image
        $disk = $public ? 'public' : 'local';
        $decodedImage = base64_decode($imageData[1]);
        $path = $directory . '/' . $filename;

        Storage::disk($disk)->put($path, $decodedImage);

        return $path;
    }

    /**
     * Process attachments for Movie model
     *
     * @param array $attachments The attachments array
     * @param string $directory The directory to store files in
     * @return array The processed attachments
     */
    public function processAttachments(array $attachments, string $directory): array
    {
        $processedAttachments = [];

        foreach ($attachments as $attachment) {
            // Skip if required fields are missing
            if (!isset($attachment['type'], $attachment['title'], $attachment['duration'])) {
                continue;
            }

            $processedAttachment = [
                'type' => $attachment['type'],
                'title' => $attachment['title'],
                'duration' => $attachment['duration'],
            ];

            // Handle file upload if it's a file
            if (isset($attachment['file']) && $attachment['file'] instanceof UploadedFile) {
                $processedAttachment['url'] = $this->storeFile(
                    $attachment['file'],
                    $directory,
                    $attachment['url'] ?? null
                );
            } else {
                // Keep existing URL or use provided URL
                $processedAttachment['url'] = $attachment['url'] ?? null;
            }

            $processedAttachments[] = $processedAttachment;
        }

        return $processedAttachments;
    }

    public function storeFileToAzure(UploadedFile $file, string $directory, ?string $oldFile = null): string
    {
        $filename = $directory . '/' . Str::ulid() . '.' . $file->getClientOriginalExtension();

        Storage::disk('azure')->put($filename, file_get_contents($file), 'public');

        if ($oldFile) {
            Storage::disk('azure')->delete($this->extractPathFromUrl($oldFile));
        }

        return $filename; // ✅ Повертаємо ТІЛЬКИ ключ без URL
    }

    public function storeBase64ImageToAzure(string $base64, string $directory, ?string $oldFilePath = null): string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            throw new \InvalidArgumentException('Невірний base64 формат');
        }

        $extension = $type[1];
        $base64Data = substr($base64, strpos($base64, ',') + 1);
        $decoded = base64_decode($base64Data);

        if ($decoded === false) {
            throw new \RuntimeException('Не вдалося декодувати base64 зображення');
        }

        $filename = $directory . '/' . Str::ulid() . '.' . $extension;

        Storage::disk('azure')->put($filename, $decoded, 'public');

        if ($oldFilePath) {
            Storage::disk('azure')->delete($this->extractPathFromUrl($oldFilePath));
        }

        return $filename; // ✅ Лише відносний шлях
    }


    protected function extractPathFromUrl(string $url): string
    {
        $prefix = "https://" . env('AZURE_STORAGE_NAME') . ".blob.core.windows.net/" . env('AZURE_STORAGE_CONTAINER') . "/";
        return str_replace($prefix, '', $url);
    }

}
