<?php

namespace App\Services\Telegram;

use App\Helpers\TelegramAuthStatus;
use App\Models\User;
use danog\MadelineProto\API;

class Authentification
{
    public function auth(User $user, $value) //API $api, array $authStatus, User $user, string $value
    {
        $sessionService = new SessionService();
        $api = $sessionService->get();
        $authStatus = TelegramAuthStatus::check();
        \Illuminate\Support\Facades\Log::info('User: ', $user->toArray());
        \Illuminate\Support\Facades\Log::info('AuthStatus: ', $authStatus);

        $actions = [
            new SendPhoneNumberAction(),
            new SendCodeAction(),
            new SendPasswordAction(),
        ];

        \Illuminate\Support\Facades\Log::info('In Class Value: ' . $value);
        foreach ($actions as $action)  {
            $action->execute($authStatus, $user, $api, $value);
        }
    }
}
