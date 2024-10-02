<?php

namespace App\Helpers;

use App\Services\Telegram\SessionService;
use danog\MadelineProto\API;

class TelegramAuthStatus
{

    public static function check()
    {
        $sessionService = new SessionService();
        $api = $sessionService->get();
        $statusCode = $api->getAuthorization();
        switch ($statusCode) {
            case '1':
                return [
                    'id' => 1,
                    'message' => 'Ожидается код для авторизации'
                ];
            case '2':
                return [
                    'id' => 2,
                    'message' => 'Ожидается пароль для авторизации'
                ];
            case '3':
                return [
                    'id' => 3,
                    'message' => 'Вы авторизованы'
                ];
            default:
                return [
                    'id' => 0,
                    'message' => 'Авторизуйтесь в телеграме.'
                ];
        }
    }
}
