<?php

namespace AnimeSite\Http\Controllers\Api\V1\Auth;

use AnimeSite\Actions\Auth\SendPasswordResetLink;
use AnimeSite\DTOs\Auth\PasswordResetLinkDTO;
use AnimeSite\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use AnimeSite\Http\Requests\Auth\PasswordResetLinkRequest;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param PasswordResetLinkRequest $request
     * @param SendPasswordResetLink $action
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request, SendPasswordResetLink $action): JsonResponse
    {

        $dto = PasswordResetLinkDTO::fromRequest($request);
        $status = $action->handle($dto);

        return response()->json(['status' => __($status)]);
    }
}
