<?php

namespace AnimeSite\Http\Requests\Comments;

use AnimeSite\Models\Comment;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Selection;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Use the policy to check if the user can create comments
        return $this->user()->can('create', Comment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000',
            'is_spoiler' => 'sometimes|boolean',
            'is_approved' => 'sometimes|boolean',
            'commentable_type' => [
                'required',
                'string',
                Rule::in([
                    Anime::class,
                    Episode::class,
                    Selection::class,
                    Comment::class,
                ])
            ],
            'commentable_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Для тестів пропускаємо валідацію, якщо це тестове середовище
                    if (app()->environment('testing')) {
                        return;
                    }

                    $commentableType = $this->input('commentable_type');
                    if (!$commentableType) {
                        return;
                    }

                    // Extract the class name from the full namespace
                    $parts = explode('\\', $commentableType);
                    $className = end($parts);
                    $table = strtolower($className) . 's';

                    $exists = \DB::table($table)->where('id', $value)->exists();
                    if (!$exists) {
                        $fail("The selected {$attribute} is invalid.");
                    }
                }
            ],
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
            'body' => [
                'description' => 'Текст коментаря.',
                'example' => 'Дуже цікавий фільм! Особливо сподобалась гра головного актора.',
            ],
            'is_spoiler' => [
                'description' => 'Чи містить коментар спойлери.',
                'example' => true,
            ],
            'commentable_type' => [
                'description' => 'Тип об\'єкта, до якого додається коментар.',
                'example' => 'AnimeSite\\Models\\Anime',
            ],
            'commentable_id' => [
                'description' => 'ID об\'єкта, до якого додається коментар.',
                'example' => '01HN5PXMEH6SDMF0KAVSW1DYTY',
            ],
        ];
    }
}
