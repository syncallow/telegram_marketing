<?php

namespace App\Services\Telegram;

use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;

class SessionService
{
    public function get()
    {
       return $this->check();
    }

    private function check()
    {
        $sessionName = env('TELEGRAM_SESSION_NAME');
        $directory = './'.$sessionName;
        if (!is_dir($directory)) {
            $app_info = (new AppInfo);
            $settings = $app_info->setApiId(env('TELEGRAM_API_ID'))->setApiHash(env('TELEGRAM_API_HASH'));
            $session = new API($sessionName, $settings);
            return $session;
        }
        return new API($sessionName);
    }
}
