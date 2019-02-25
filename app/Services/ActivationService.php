<?php

namespace App\Services;

use App\Notifications\ActivateAccount;
use App\Models\User;
use App\Services\TokenActivationService;
use Illuminate\Contracts\Mail\Mailer;

class ActivationService
{
    private const RESEND_AFTER = 24;

    /**
     * @var TokenActivationService
     */
    private $tokenService;

    public function __construct(TokenActivationService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function sendUserActivationMail(User $user): void
    {
        if ($user->isActivated() || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->tokenService->createActivation($user);

        $user->notify(new ActivateAccount($token));
    }

    public function resendUserActivationMail(User $user): void
    {
        if ($user->isActivated()) {
            return;
        }

        $activation = $this->tokenService->getActivation($user);

        if ($activation){
            $this->tokenService->deleteActivation($activation->token);
        }

        $token = $this->tokenService->createActivation($user);

        $user->notify(new ActivateAccount($token));
    }

    public function activateUser(string $token): ?User
    {
        $activation = $this->tokenService->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        /** @var User $user */
        $user = User::findOrFail($activation->entity_id);
        $user->activate();
        $user->save();

        $this->tokenService->deleteActivation($token);

        return $user;
    }

    private function shouldSend(User $user): bool
    {
        $activation = $this->tokenService->getActivation($user);

        return $activation === null || strtotime($activation->created_at) + 60 * 60 * self::RESEND_AFTER < time();
    }

}