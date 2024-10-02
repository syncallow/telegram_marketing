<?php

namespace App\Jobs;

use App\Models\Post;
use Carbon\Carbon;
use danog\MadelineProto\API;
use danog\MadelineProto\RemoteUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class SendPostsJob implements ShouldQueue
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
        $api = new API(env('TELEGRAM_SESSION_NAME'));
        $post = Post::where('enable_to_send', true)
                     ->where(function ($query) {
                         $query->whereNull('last_sent')
                                 ->orWhere('last_sent', '<' , now()->subHour());
                     })
                     ->orderBy('last_sent', 'asc')
                     ->first();
        //$date = Carbon::now();
        $authStatus = Cache::get('auth-info');
        if ($authStatus === null || $authStatus['id'] !== 3) throw new \Exception('Проблема авторизации: ' . $authStatus['message'] ?? 'Неизвестная ошибка');
        if ($post) {
            $file = new RemoteUrl($post->image);
            $peers = $post->chats;
            foreach ($peers as $peer) {
                try {
                    $api->sendPhoto($peer->chat_id, $file, $post->description);
                } catch (\Exception $e) {
                    Log::error('Ошибка отправки поста: ' . $e->getMessage());
                    continue;
                }
            }
            $post->update(['last_sent' => now()]);
        } else {
            Log::info('Нечего отправлять за час');
        }
    }
}
