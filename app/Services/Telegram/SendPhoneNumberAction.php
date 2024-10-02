<?php

namespace App\Services\Telegram;

use App\Jobs\TelegramCheckAuthStatusJob;
use App\Models\User;
use App\Services\NotificationService;
use danog\MadelineProto\API;
use danog\MadelineProto\RPCErrorException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendPhoneNumberAction implements AuthAction
{

    public function execute($authStatus, User $user, API $api, $value): void
    {
        if ($authStatus['id'] === 0) {
            $notificationService = new NotificationService();
            Log::info('Send Phone fnc: ' . $value);
            try {
                $api->phoneLogin($value);
            } catch (RPCErrorException $exception) {
                TelegramCheckAuthStatusJob::dispatch();
                $msg = $exception->getMessage();
                if ($exception->getMessage() === 'PHONE_NUMBER_INVALID') $msg = 'Неправильный номер телефона';
                if ($exception->getMessage() === 'PHONE_CODE_EXPIRED') $msg = 'Код устарел. Войдите заново!';
                Log::error('Ошибка при входе по номеру телефона: ' . $msg);
                $notificationService->sendErrorNotification($user, $msg);
                throw $exception;

            }
            TelegramCheckAuthStatusJob::dispatch();
            $notificationService->sendPhoneNotification($user);
            Log::info('Данные с SendPhoneAction: ', $user->toArray());
        }
    }
}
