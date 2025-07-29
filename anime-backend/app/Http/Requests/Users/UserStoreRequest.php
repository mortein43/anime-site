<?php

namespace AnimeSite\Http\Requests\Users;

use AnimeSite\Enums\Gender;
use AnimeSite\Enums\Role;
use AnimeSite\Rules\FileOrString;
use AnimeSite\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Use the policy to check if the user can create users
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'role' => ['required', new Enum(Role::class)],
            'gender' => ['nullable', new Enum(Gender::class)],
            'avatar' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 5120)],
            'backdrop' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
            'description' => ['nullable', 'string', 'max:1000'],
            'birthday' => ['nullable', 'date'],
            'allow_adult' => ['sometimes', 'boolean'],
            'is_auto_next' => ['sometimes', 'boolean'],
            'is_auto_play' => ['sometimes', 'boolean'],
            'is_auto_skip_intro' => ['sometimes', 'boolean'],
            'is_private_favorites' => ['sometimes', 'boolean'],
            'is_banned' => ['sometimes', 'boolean'],

            // Notification settings
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

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Ім\'\u044f користувача.',
                'example' => 'John Doe',
            ],
            'email' => [
                'description' => 'Електронна пошта користувача (має бути унікальною).',
                'example' => 'john.doe@example.com',
            ],
            'password' => [
                'description' => 'Пароль користувача.',
                'example' => 'StrongPassword123!',
            ],
            'password_confirmation' => [
                'description' => 'Підтвердження пароля.',
                'example' => 'StrongPassword123!',
            ],
            'role' => [
                'description' => 'Роль користувача.',
                'example' => 'USER',
            ],
            'gender' => [
                'description' => 'Стать користувача (необов\'язково).',
                'example' => 'MALE',
            ],
            'avatar' => [
                'description' => 'Аватар користувача (файл або URL, необов\'язково).',
                'example' => 'https://example.com/avatar.jpg',
            ],
            'backdrop' => [
                'description' => 'Фонове зображення користувача (файл або URL, необов\'язково).',
                'example' => 'https://example.com/backdrop.jpg',
            ],
            'description' => [
                'description' => 'Опис профілю користувача (необов\'язково).',
                'example' => 'Люблю дивитись фільми та серіали у вільний час.',
            ],
            'birthday' => [
                'description' => 'Дата народження користувача (необов\'язково).',
                'example' => '1990-01-01',
            ],
            'allow_adult' => [
                'description' => 'Чи дозволений контент для дорослих.',
                'example' => true,
            ],
            'is_auto_next' => [
                'description' => 'Чи автоматично переходити до наступного епізоду.',
                'example' => true,
            ],
            'is_auto_play' => [
                'description' => 'Чи автоматично відтворювати відео.',
                'example' => true,
            ],
            'is_auto_skip_intro' => [
                'description' => 'Чи автоматично пропускати інтро.',
                'example' => true,
            ],
            'is_private_favorites' => [
                'description' => 'Чи є список улюблених приватним.',
                'example' => false,
            ],
            'is_banned' => [
                'description' => 'Чи заблокований користувач.',
                'example' => false,
            ],
            'notify_new_episodes' => [
                'description' => 'Чи отримувати сповіщення про нові епізоди.',
                'example' => true,
            ],
            'notify_episode_date_changes' => [
                'description' => 'Чи отримувати сповіщення про зміну дати виходу епізодів.',
                'example' => true,
            ],
            'notify_announcement_to_ongoing' => [
                'description' => 'Чи повідомляти про початок трансляції анонсованого аніме.',
                'example' => true,
            ],
            'notify_comment_replies' => [
                'description' => 'Чи отримувати сповіщення про відповіді на коментарі.',
                'example' => true,
            ],
            'notify_comment_likes' => [
                'description' => 'Чи отримувати сповіщення про вподобання коментарів.',
                'example' => false,
            ],
            'notify_review_replies' => [
                'description' => 'Чи отримувати сповіщення про відповіді на рецензії.',
                'example' => true,
            ],
            'notify_planned_reminders' => [
                'description' => 'Чи отримувати нагадування про заплановане.',
                'example' => false,
            ],
            'notify_new_selections' => [
                'description' => 'Чи повідомляти про нові добірки.',
                'example' => true,
            ],
            'notify_status_changes' => [
                'description' => 'Чи повідомляти про зміну статусу аніме.',
                'example' => true,
            ],
            'notify_new_seasons' => [
                'description' => 'Чи повідомляти про нові сезони аніме.',
                'example' => true,
            ],
            'notify_subscription_expiration' => [
                'description' => 'Чи сповіщати про завершення підписки.',
                'example' => true,
            ],
            'notify_subscription_renewal' => [
                'description' => 'Чи сповіщати про оновлення підписки.',
                'example' => true,
            ],
            'notify_payment_issues' => [
                'description' => 'Чи повідомляти про проблеми з оплатою.',
                'example' => false,
            ],
            'notify_tariff_changes' => [
                'description' => 'Чи сповіщати про зміну тарифів.',
                'example' => true,
            ],
            'notify_site_updates' => [
                'description' => 'Чи отримувати сповіщення про оновлення сайту.',
                'example' => true,
            ],
            'notify_maintenance' => [
                'description' => 'Чи отримувати сповіщення про технічне обслуговування.',
                'example' => true,
            ],
            'notify_security_changes' => [
                'description' => 'Чи отримувати сповіщення про зміни в системі безпеки.',
                'example' => true,
            ],
            'notify_new_features' => [
                'description' => 'Чи повідомляти про нові функції на сайті.',
                'example' => true,
            ],
        ];
    }
}
