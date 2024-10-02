<?php

namespace App\Jobs;

use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GetChatInfoJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $id = '-4543375740';

        $madelineProto = new API(env('TELEGRAM_SESSION_NAME'));
        $madelineProto->start();
        $chat = $madelineProto->getInfo($id);
        \Illuminate\Support\Facades\Log::info('Chats info new:', $chat);
        Cache::put('chat_info', $chat);
    }
}
