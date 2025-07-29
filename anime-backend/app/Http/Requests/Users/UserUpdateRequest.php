<?php

namespace AnimeSite\Http\Requests\Users;

use AnimeSite\Enums\Gender;
use AnimeSite\Rules\FileOrString;
use AnimeSite\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        // Дозволити доступ під час генерації документації
        if (!$user) {
            return true;
        }

        return $this->user() && $this->user()->can('update', $user);
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user ? $user->id : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                $userId ? Rule::unique('users')->ignore($userId) : Rule::unique('users'),
            ],
            'password' => ['sometimes', 'string', Password::defaults(), 'confirmed'],
            'role' => ['sometimes', new Enum(Role::class)],
            'gender' => ['sometimes', 'nullable', new Enum(Gender::class)],
            'avatar' => ['sometimes', 'nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 5120)],
            'backdrop' => ['sometimes', 'nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'birthday' => ['sometimes', 'nullable', 'date'],
            'allow_adult' => ['sometimes', 'boolean'],
            'is_auto_next' => ['sometimes', 'boolean'],
            'is_auto_play' => ['sometimes', 'boolean'],
            'is_auto_skip_intro' => ['sometimes', 'boolean'],
            'is_private_favorites' => ['sometimes', 'boolean'],
            'is_banned' => ['sometimes', 'boolean'],

            // Notification preferences
            'notify_new_episodes' => ['sometimes', 'boolean'],
            'notify_episode_date_changes' => ['sometimes', 'boolean'],
            'notify_announcement_to_ongoing' => ['sometimes', 'boolean'],
            'notify_comment_replies' => ['sometimes', 'boolean'],
            'notify_comment_likes' => ['sometimes', 'boolean'],
            'notify_review_replies' => ['sometimes', 'boolean'],
            'notify_planned_reminders' => ['sometimes', 'boolean'],
            'notify_new_selections' => ['sometimes', 'boolean'],
            'notify_status_changes' => ['sometimes', 'boolean'],
            'notify_new_seasons' => ['sometimes', 'boolean'],
            'notify_subscription_expiration' => ['sometimes', 'boolean'],
            'notify_subscription_renewal' => ['sometimes', 'boolean'],
            'notify_payment_issues' => ['sometimes', 'boolean'],
            'notify_tariff_changes' => ['sometimes', 'boolean'],
            'notify_site_updates' => ['sometimes', 'boolean'],
            'notify_maintenance' => ['sometimes', 'boolean'],
            'notify_security_changes' => ['sometimes', 'boolean'],
            'notify_new_features' => ['sometimes', 'boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => "Ім'я користувача", 'example' => 'Іван Петренко'],
            'email' => ['description' => 'Електронна пошта користувача', 'example' => 'user@example.com'],
            'password' => ['description' => 'Новий пароль користувача', 'example' => 'password123'],
            'password_confirmation' => ['description' => 'Підтвердження нового пароля', 'example' => 'password123'],
            'role' => ['description' => 'Роль користувача (тільки для адміністраторів)', 'example' => 'user'],
            'gender' => ['description' => 'Стать користувача', 'example' => 'male'],
            'avatar' => ['description' => 'Аватар користувача (URL або файл)', 'example' => 'https://example.com/avatar.jpg'],
            'backdrop' => ['description' => 'Фонове зображення профілю користувача (URL або файл)', 'example' => 'https://example.com/backdrop.jpg'],
            'description' => ['description' => 'Опис профілю користувача', 'example' => 'Люблю дивитися фільми та серіали'],
            'birthday' => ['description' => 'Дата народження користувача', 'example' => '1990-01-01'],
            'allow_adult' => ['description' => 'Дозволити контент для дорослих', 'example' => true],
            'is_auto_next' => ['description' => 'Автоматично переходити до наступного епізоду', 'example' => true],
            'is_auto_play' => ['description' => 'Автоматично відтворювати відео', 'example' => true],
            'is_auto_skip_intro' => ['description' => 'Автоматично пропускати вступ', 'example' => true],
            'is_private_favorites' => ['description' => 'Зробити список улюблених приватним', 'example' => false],
            'is_banned' => ['description' => 'Заблокувати користувача (тільки для адміністраторів)', 'example' => false],

            // Notification fields
            'notify_new_episodes' => ['description' => 'Сповіщати про нові епізоди', 'example' => true],
            'notify_episode_date_changes' => ['description' => 'Сповіщати про зміну дат виходу епізодів', 'example' => true],
            'notify_announcement_to_ongoing' => ['description' => 'Сповіщати про початок трансляції анонсованих тайтлів', 'example' => true],
            'notify_comment_replies' => ['description' => 'Сповіщати про відповіді на коментарі', 'example' => true],
            'notify_comment_likes' => ['description' => 'Сповіщати про вподобання коментарів', 'example' => false],
            'notify_review_replies' => ['description' => 'Сповіщати про відповіді на рецензії', 'example' => true],
            'notify_planned_reminders' => ['description' => 'Нагадування про заплановані перегляди', 'example' => false],
            'notify_new_selections' => ['description' => 'Сповіщати про нові добірки', 'example' => true],
            'notify_status_changes' => ['description' => 'Сповіщати про зміну статусу тайтлу', 'example' => true],
            'notify_new_seasons' => ['description' => 'Сповіщати про нові сезони', 'example' => true],
            'notify_subscription_expiration' => ['description' => 'Сповіщати про завершення підписки', 'example' => true],
            'notify_subscription_renewal' => ['description' => 'Сповіщати про поновлення підписки', 'example' => true],
            'notify_payment_issues' => ['description' => 'Сповіщати про проблеми з оплатою', 'example' => false],
            'notify_tariff_changes' => ['description' => 'Сповіщати про зміну тарифного плану', 'example' => true],
            'notify_site_updates' => ['description' => 'Сповіщати про оновлення сайту', 'example' => true],
            'notify_maintenance' => ['description' => 'Сповіщати про технічні роботи', 'example' => true],
            'notify_security_changes' => ['description' => 'Сповіщати про зміни в системі безпеки', 'example' => true],
            'notify_new_features' => ['description' => 'Сповіщати про нові функції сайту', 'example' => true],
        ];
    }
}
