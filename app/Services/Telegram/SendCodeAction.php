<?php

namespace App\Services\Telegram;

use App\Jobs\TelegramCheckAuthStatusJob;
use App\Models\User;
use App\Services\NotificationService;
use danog\MadelineProto\API;
use danog\MadelineProto\RPCErrorException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendCodeAction implements AuthAction
{
    public function execute($authStatus, User $user, API $api, $value): void
    {
        if ($authStatus['id'] === 1) {
            $notificationService = new NotificationService();
            Log::info('Send Code fnc: ' . $value);
            try {
                $api->completePhoneLogin($value);
            } catch (RPCErrorException $exception) {
                TelegramCheckAuthStatusJob::dispatch();
                $msg = $exception->getMessage();
                if ($exception->getMessage() === 'PHONE_CODE_INVALID') $msg = 'Неправильный код';
                Log::error('Ошибка при входе по номеру телефона: ' . $msg);
                $notificationService->sendErrorNotification($user, $msg);
                throw $exception;
            }
            TelegramCheckAuthStatusJob::dispatch();
            $notificationService->sendCodeNotification($user);
        }
    }
}
