<?php

namespace App\Console\Commands\Telegram;

use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Console\Command;

class AuthStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auth-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows Telegram Auth status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $app_info = (new AppInfo);
        $settings = $app_info->setApiId(env('TELEGRAM_API_ID'))->setApiHash(env('TELEGRAM_API_HASH'));
        $api = new API(env('TELEGRAM_SESSION_NAME'), $settings);
        echo $api->getAuthorization() . PHP_EOL;
    }
}
