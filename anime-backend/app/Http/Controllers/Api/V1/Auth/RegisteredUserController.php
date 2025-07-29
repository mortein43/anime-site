<?php

namespace AnimeSite\Http\Controllers\Api\V1\Auth;

use AnimeSite\Actions\Auth\RegisterUser;
use AnimeSite\DTOs\Auth\RegisterDTO;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use AnimeSite\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param RegisterRequest $request
     * @param RegisterUser $action
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(RegisterRequest $request, RegisterUser $action): JsonResponse
    {
        $dto = RegisterDTO::fromRequest($request);
        $user = $action->handle($dto);

        // Створюємо новий токен через Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }
}
