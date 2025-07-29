<?php

namespace AnimeSite\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use AnimeSite\Models\Comment;
use AnimeSite\Models\CommentLike;
use AnimeSite\Models\CommentReport;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Rating;
use AnimeSite\Models\Selection;
use AnimeSite\Models\Studio;
use AnimeSite\Models\Tag;
use AnimeSite\Models\Tariff;
use AnimeSite\Models\User;
use AnimeSite\Models\UserList;
use AnimeSite\Policies\CommentLikePolicy;
use AnimeSite\Policies\CommentPolicy;
use AnimeSite\Policies\CommentReportPolicy;
use AnimeSite\Policies\EpisodePolicy;
use AnimeSite\Policies\AnimePolicy;
use AnimeSite\Policies\PersonPolicy;
use AnimeSite\Policies\RatingPolicy;
use AnimeSite\Policies\SelectionPolicy;
use AnimeSite\Policies\StudioPolicy;
use AnimeSite\Policies\TagPolicy;
//use AnimeSite\Policies\TariffPolicy;
use AnimeSite\Policies\UserListPolicy;
use AnimeSite\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => CommentPolicy::class,
        CommentLike::class => CommentLikePolicy::class,
        CommentReport::class => CommentReportPolicy::class,
        Episode::class => EpisodePolicy::class,
        Anime::class => AnimePolicy::class,
        Person::class => PersonPolicy::class,
        Rating::class => RatingPolicy::class,
        Selection::class => SelectionPolicy::class,
        Studio::class => StudioPolicy::class,
        Tag::class => TagPolicy::class,
        //Tariff::class => TariffPolicy::class,
        User::class => UserPolicy::class,
        UserList::class => UserListPolicy::class,
    ];
    public function register(): void {
        Storage::extend('azure', function ($AnimeSite, $config) {
            $client = BlobRestProxy::createBlobService($config['connection_string']);
            $adapter = new AzureBlobStorageAdapter($client, $config['container']);
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config
            );
        });
    }

    public function boot(): void
    {
        putenv('CURL_CA_BUNDLE=D:/laragon/etc/ssl/cacert.pem');
        Model::unguard();
        Model::shouldBeStrict();
//        \Log::info('AnimeSiteServiceProvider boot called');
        Storage::extend('azure', function ($AnimeSite, $config) {
            $client = BlobRestProxy::createBlobService($config['connection_string']);
            $adapter = new AzureBlobStorageAdapter($client, $config['container']);
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config
            );
        });
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        Model::unguard();
        Model::shouldBeStrict();

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Blueprint::macro('enumAlterColumn',
            function (
                string $columnName,
                string $enumTypeName,
                string $enumClass,
                ?string $default = null,
                bool $nullable = false
            ) {
                // Генеруємо список значень enum
                $value = collect($enumClass::cases())
                    ->map(fn($case) => "'{$case->value}'")
                    ->implode(',');

                // Створюємо тип enum, якщо він ще не існує
                DB::statement(sprintf(
                    "DO $$ BEGIN
                                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = '%s') THEN
                                    CREATE TYPE %s AS ENUM (%s);
                                END IF;
                            END $$;",
                    $enumTypeName,
                    $enumTypeName,
                    $value
                ));

                // Додаємо стовпець з типом enum та nullable, якщо це необхідно
                $nullableClause = $nullable ? 'NULL' : 'NOT NULL';

                DB::statement(sprintf(
                    'ALTER TABLE "%s" ADD COLUMN "%s" %s %s;',
                    $this->getTable(),
                    $columnName,
                    $enumTypeName,
                    $nullableClause
                ));

                // Якщо задано значення за замовчуванням, додаємо його
                if ($default) {
                    DB::statement(sprintf(
                        'ALTER TABLE "%s" ALTER COLUMN "%s" SET DEFAULT %s;',
                        $this->getTable(),
                        $columnName,
                        "'{$default}'"
                    ));
                }
            });
    }
}
