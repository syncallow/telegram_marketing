<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Http\Requests\Chat\UpdateRequest;
use App\Http\Resources\ChatResource;
use App\Jobs\StoreChatJob;
use App\Models\Chat;
use danog\MadelineProto\API;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return inertia('Chat/Index', compact('chats'));
    }

    public function show(Request $request)
    {
        $request->validate([
            'page' => 'required|integer'
        ]);
        return ChatResource::collection(Chat::paginate(10, ['*'], 'page', $request->page));
    }

    public function getAll()
    {
        return ChatResource::collection(Chat::all());
    }

    public function create()
    {
        return inertia('Chat/Create');
    }

    public function edit(Chat $chat)
    {
        return inertia('Chat/Edit', compact('chat'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        StoreChatJob::dispatch($data);
        return redirect()->back();
    }

    public function update(UpdateRequest $request, Chat $chat)
    {
        $data = $request->validated();
        $chat->update([
            'chat_id' => $data['id']
        ]);
       return redirect()->back();
    }

    public function delete(Chat $chat)
    {
        $chat->delete();
       return redirect()->back();
    }
}
