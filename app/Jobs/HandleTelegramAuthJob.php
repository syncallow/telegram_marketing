<?php

namespace App\Jobs;

use App\Helpers\TelegramAuthStatus;
use App\Models\User;
use App\Services\Telegram\Authentification;
use App\Services\Telegram\SendCodeAction;
use App\Services\Telegram\SendPasswordAction;
use App\Services\Telegram\SendPhoneNumberAction;
use App\Services\Telegram\SessionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Reverb\Loggers\Log;

class HandleTelegramAuthJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user, protected $value)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $authentication = new Authentification();
        $authentication->auth($this->user, $this->value);
    }
}
