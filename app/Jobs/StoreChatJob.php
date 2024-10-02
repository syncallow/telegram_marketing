<?php

namespace App\Jobs;

use App\Models\Chat;
use App\Models\Post;
use Carbon\Carbon;
use danog\MadelineProto\API;
use danog\MadelineProto\ParseMode;
use danog\MadelineProto\RemoteUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreChatJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $api = new API(env('TELEGRAM_SESSION_NAME'));
        $chatInfo = $api->getInfo($this->data['id']);
        try {
            DB::beginTransaction();

                if ($chatInfo['type'] === 'user') $title = $chatInfo['User']['first_name'];
                if ($chatInfo['type'] === 'supergroup') $title = $chatInfo['Chat']['title'];
                if ($chatInfo['type'] === 'chat') $title = $chatInfo['Chat']['title'];
                $chat = Chat::create([
                    'chat_id' => $this->data['id'],
                    'title' => $title ?? 'Null'
                ]);
                Log::info('Добавлена группа: '. $chat->title);
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Ошибка добавления чата: ' . $exception->getMessage());
            throw $exception;
        }
    }
}
