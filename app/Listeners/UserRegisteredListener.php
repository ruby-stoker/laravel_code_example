<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Services\ActivationService;

class UserRegisteredListener
{
    /** @var ActivationService */
    private $activationService;

    public function __construct(ActivationService $activationService) {
        $this->activationService = $activationService;
    }

    /**
     * Handle the event.
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event): void
    {
        $this->activationService->sendUserActivationMail($event->user);
    }
}
