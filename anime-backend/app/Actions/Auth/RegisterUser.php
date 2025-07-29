<?php

namespace AnimeSite\Actions\Auth;

use AnimeSite\DTOs\Auth\RegisterDTO;
use AnimeSite\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUser
{
    use AsAction;

    /**
     * Register a new user.
     *
     * @param RegisterDTO $dto
     * @return User
     */
    public function handle(RegisterDTO $dto): User
    {
        $name = $dto->name ?? strstr($dto->email, '@', true);

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        // Dispatch registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        return $user;
    }
}
