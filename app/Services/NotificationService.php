<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\CodeSentNotification;
use App\Notifications\ErrorNotification;
use App\Notifications\PasswordSentNotification;
use App\Notifications\PhoneSentNotification;

class NotificationService
{
    public function sendPhoneNotification(User $user) : void
    {
        $user->notify(new PhoneSentNotification());
    }
    public function sendCodeNotification(User $user) : void
    {
        $user->notify(new CodeSentNotification());
    }

    public function sendPasswordNotification(User $user) : void
    {
        $user->notify(new PasswordSentNotification());
    }

    public function sendErrorNotification(User$user, string $message) : void
    {
        $user->notify(new ErrorNotification($message));
    }
}
