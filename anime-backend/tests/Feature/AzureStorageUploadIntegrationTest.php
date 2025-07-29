<?php

namespace Tests\Feature;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class AzureStorageUploadIntegrationTest extends TestCase
{
    public function test_real_file_upload_to_azure()
    {
        // НЕ мокаємо диск, працюємо з реальним Azure
        // Створюємо фейковий файл в пам'яті
        $file = UploadedFile::fake()->image('real_test.jpg');

        // Зберігаємо у контейнер Azure у потрібну папку
        $path = Storage::disk('azure')->putFile('studios/images', $file);

        // Перевіряємо, що шлях повернувся і файл існує (для Azure цей check обмежений)
        $this->assertNotEmpty($path);

        // Для додаткової перевірки можна спробувати завантажити файл назад
        $contents = Storage::disk('azure')->get($path);
        $this->assertNotEmpty($contents);
    }
}
