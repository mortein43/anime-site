<?php

namespace AnimeSite\Http\Controllers\Api\V1\Auth;

use AnimeSite\Actions\Auth\VerifyEmail;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param EmailVerificationRequest $request
     * @param VerifyEmail $action
     * @return RedirectResponse
     * @authenticated
     */
    public function __invoke(EmailVerificationRequest $request, VerifyEmail $action): RedirectResponse
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(config('app.frontend_url').'/?verified=1')
            : $this->verifyEmail($request, $action);
    }

    /**
     * Verify the user's email address.
     *
     * @param EmailVerificationRequest $request
     * @param VerifyEmail $action
     * @return RedirectResponse
     */
    private function verifyEmail(EmailVerificationRequest $request, VerifyEmail $action): RedirectResponse
    {
        $action->handle($request);

        return redirect()->intended(config('app.frontend_url').'/?verified=1');
    }
}
