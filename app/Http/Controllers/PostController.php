<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Resources\PostResource;
use App\Jobs\SendSinglePostJob;
use App\Models\Post;
use App\Models\PostChat;
use danog\MadelineProto\API;
use danog\MadelineProto\ParseMode;
use danog\MadelineProto\RemoteUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{

    public function index()
    {
        return inertia('Posts/Index');
    }

    public function create()
    {
        return inertia('Posts/Create');
    }

    public function show(Request $request)
    {
        $request->validate([
            'page' => 'required|integer'
        ]);
        return PostResource::collection(Post::paginate(10, ['*'], 'page', $request->page));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $image = $data['image'];
        $res = Http::attach(
            'image', file_get_contents($image->getRealPath()), $image->getClientOriginalName()
        )->post('https://api.imgbb.com/1/upload', [
            'key' => env('IMGBB_API_KEY'),
        ]);
        if (!$res['success']) return $res->json();
        $data['image'] = $res['data']['url'];
        $chatIds = $data['chat_ids'];
        unset($data['chat_ids']);
        $post = Post::create($data);
        $postChats = array_map(function ($item) use ($post) {
            return [
                'post_id' => $post->id,
                'chat_id' => $item
            ];
        }, $chatIds);
        PostChat::insert($postChats);
        return redirect()->back();
    }

    public function edit(Post $post)
    {
        $post = PostResource::make($post)->resolve();
        return inertia('Posts/Edit', compact('post'));
    }

    public function update(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();
        if (isset($data['image'])) {
            $image = $data['image'];
            $res = Http::attach(
                'image', file_get_contents($image->getRealPath()), $image->getClientOriginalName()
            )->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
            ]);
            if (!$res['success']) return $res->json();
            $data['image'] = $res['data']['url'];
        }
        if (!isset($data['image'])) unset($data['image']);
        $chatIds = $data['chat_ids'];
        unset($data['chat_ids']);
        $post->update($data);
        $post->chats()->sync($chatIds);
        return redirect()->back();
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('success', 'Запись удалена успешно.');
    }

    public function send(Post $post)
    {
        SendSinglePostJob::dispatch($post);
        return response()->json(['message' => 'Рассылка успешна'], 200);
    }
}
