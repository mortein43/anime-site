<?php

namespace AnimeSite\Http\Controllers\Api\V1\Auth;
use AnimeSite\Http\Resources\UserResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use AnimeSite\Models\User;
class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName() ?? strstr($googleUser->getEmail(), '@', true),
                    'password' => Hash::make(Str::random(16)),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(), // Google emails are verified
                ]
            );

            // Create Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Redirect to frontend with token
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');

            return redirect()->to(
                $frontendUrl . '/auth/callback?token=' . $token . '&user=' . urlencode(json_encode(new UserResource($user)))
            );

        } catch (\Exception $e) {
            // Redirect to frontend with error
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
            return redirect()->to($frontendUrl . '/auth/callback?error=' . urlencode('Authentication failed'));
        }
    }
}
