<?php

namespace AnimeSite\Http\Requests\UserLists;

use AnimeSite\Enums\UserListType;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use AnimeSite\Models\Tag;
use AnimeSite\Models\UserList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreUserListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', UserList::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'exists:users,id'],
            'type' => ['required', new Enum(UserListType::class)],
            'listable_type' => [
                'required',
                'string',
                Rule::in([
                    Anime::class,
                    Episode::class,
                    Person::class,
                    Tag::class,
                    Selection::class,
                ])
            ],
            'listable_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $listableType = $this->input('listable_type');
                    if (!$listableType) {
                        return;
                    }

                    // Extract the class name from the full namespace
                    $parts = explode('\\', $listableType);
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
            'type' => [
                'description' => 'Тип списку користувача (FAVORITES, WATCH_LATER, тощо).',
                'example' => 'FAVORITES',
            ],
            'listable_type' => [
                'description' => 'Тип об\'єкта, який додається до списку.',
                'example' => 'AnimeSite\\Models\\Anime',
            ],
            'listable_id' => [
                'description' => 'ID об\'єкта, який додається до списку.',
                'example' => '01HN5PXMEH6SDMF0KAVSW1DYTY',
            ],
        ];
    }
}
