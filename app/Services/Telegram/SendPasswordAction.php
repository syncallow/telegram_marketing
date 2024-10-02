<?php

namespace App\Services\Telegram;

use App\Jobs\TelegramCheckAuthStatusJob;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\CarbonInterval;
use danog\MadelineProto\API;
use danog\MadelineProto\RPCErrorException;
use Illuminate\Support\Facades\Log;

class SendPasswordAction implements AuthAction
{
    public function execute($authStatus, User $user, API $api, $value): void
    {
        if ($authStatus['id'] === 2) {
            $notificationService = new NotificationService();
            Log::info('Send password func:' . $value);
            try {
                $api->complete2faLogin($value);
            } catch (RPCErrorException $exception) {
                TelegramCheckAuthStatusJob::dispatch();
                $msg = $exception->getMessage();
                if (str_contains($msg, 'FLOOD_WAIT_')) {

                    preg_match('/FLOOD_WAIT_(\d+)/', $msg, $matches);
                    if (isset($matches[1])) {
                        $waitTime = (int) $matches[1];
                        $interval = CarbonInterval::seconds($waitTime)->cascade();
                        $msg = 'Нужно подождать '. $interval->forHumans();
                    } else {
                        $msg = 'Нужно подождать';
                    }
                }
                if ($msg === 'PASSWORD_HASH_INVALID') $msg = 'Неверный пароль';
                Log::error('Ошибка при вводе пароля: ' . $msg);
                $notificationService->sendErrorNotification($user, $msg);
                throw $exception;
            }
            TelegramCheckAuthStatusJob::dispatch();
            $notificationService->sendPasswordNotification($user);
        }
    }
}
