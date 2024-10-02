<?php

namespace App\Jobs;

use danog\MadelineProto\API;
use danog\MadelineProto\RemoteUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSinglePostJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $post)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $api = new API(env('TELEGRAM_SESSION_NAME'));
            $peers = $this->post->chats;
            $file = new RemoteUrl($this->post->image);
            foreach ($peers as $peer) {
                $api->sendPhoto($peer->chat_id, $file, $this->post->description);
            }
        } catch (\Exception $e) {
            Log::error('Ошибка отправки поста: ' . $e->getMessage());
            throw  $e;
        }
    }
}
