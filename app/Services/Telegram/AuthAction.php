<?php

namespace App\Services\Telegram;

use App\Models\User;
use danog\MadelineProto\API;

interface AuthAction
{
    public function execute($authStatus,User $user, API $api, $value);
}
