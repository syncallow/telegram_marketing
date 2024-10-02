<?php

use App\Http\Controllers\ProfileController;
use danog\MadelineProto\API;
use danog\MadelineProto\RPCErrorException;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return \inertia('Index');
})->middleware('auth')->name('app.index');

Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
    Route::get('/create', [\App\Http\Controllers\PostController::class, 'create'])->name('posts.create');
    Route::post('/', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::post('/show', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');
    Route::get('/edit/{post}', [\App\Http\Controllers\PostController::class, 'edit'])->name('posts.edit');
    Route::post('/update/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('posts.update');
    Route::post('/send/{post}', [\App\Http\Controllers\PostController::class, 'send'])->name('posts.send');
    Route::delete('/{post}', [\App\Http\Controllers\PostController::class, 'delete'])->name('posts.delete');
});
Route::group(['prefix' => 'chats'], function () {
    Route::get('/', [\App\Http\Controllers\ChatController::class, 'index'])->name('chats.index');
    Route::get('/create', [\App\Http\Controllers\ChatController::class, 'create'])->name('chats.create');
    Route::get('/get/all', [\App\Http\Controllers\ChatController::class, 'getAll'])->name('chats.get.all');
    Route::post('/show', [\App\Http\Controllers\ChatController::class, 'show'])->name('chats.show');
    Route::post('/', [\App\Http\Controllers\ChatController::class, 'store'])->name('chats.store');
    Route::get('/edit/{chat}', [\App\Http\Controllers\ChatController::class, 'edit'])->name('chats.edit');
    Route::patch('/{chat}', [\App\Http\Controllers\ChatController::class, 'update'])->name('chats.update');
    Route::delete('/{chat}', [\App\Http\Controllers\ChatController::class, 'delete'])->name('chats.delete');
});

//Route::get('/auth-info', function (){
//    $post = \App\Models\Post::find(1);
//    $date = \Carbon\Carbon::now()->subMonth();
//    $postDate = \Carbon\Carbon::parse($post->last_sent);
//    dd(round($date->diffInMonths($postDate)), round($date->diffInWeeks($postDate)), round($date->diffInDays($postDate)));
//    return response()->json(\App\Helpers\TelegramAuthStatus::check());
//});

Route::get('/chat-info', function (){
    $id = '-1001560439901';
    $madelineProto = new API(env('TELEGRAM_SESSION_NAME'));

    $chat = $madelineProto->getInfo($id);
    if ($chat['type'] === 'channel') return 'no channel pls';
    if ($chat['type'] === 'user') return $chat['User']['first_name'];
    return $chat['Chat']['title'];
});

Route::group(['prefix' => 'telegram'], function () {
    Route::get('/auth', [\App\Http\Controllers\Telegram\AuthController::class, 'index']);
    Route::post('/auth', [\App\Http\Controllers\Telegram\AuthController::class, 'login'])->name('telegram.login');
    Route::post('/status', [\App\Http\Controllers\Telegram\AuthController::class, 'getStatus'])->name('telegram.status');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
