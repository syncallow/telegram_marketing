<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Jobs\HandleTelegramAuthJob;
use App\Services\Telegram\Authentification;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct(protected Authentification $authentification)
    {

    }

    public function index()
    {
        return inertia('Telegram/Auth');
    }

    public function login(Request $request)
    {
        $request->validate([
            'value' => 'required'
        ]);
        $user = auth()->user();
        HandleTelegramAuthJob::dispatch($user, $request->value);
        return response()->json('Ok');
    }

    public function getStatus()
    {
        return response()->json(Cache::get('auth-info'));
    }
}
