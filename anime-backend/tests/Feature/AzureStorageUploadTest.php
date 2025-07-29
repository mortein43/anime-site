<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AzureStorageUploadTest extends TestCase
{
    public function test_file_upload_to_azure_disk()
    {
        // Мокаємо диск 'azure'
        Storage::fake('azure');

        // Створюємо фейковий файл
        $file = UploadedFile::fake()->image('test.jpg');

        // Шлях збереження
        $path = 'studios/images/' . $file->hashName();

        // Записуємо файл на диск 'azure'
        $result = Storage::disk('azure')->putFileAs('studios/images', $file, $file->hashName());

        // Перевіряємо, що файл був записаний
        Storage::disk('azure')->assertExists($path);

        $this->assertEquals($path, $result);
    }
}
