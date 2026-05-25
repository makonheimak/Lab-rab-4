<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageCollection;
use App\Http\Resources\ChatMessageResource;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Chat", description="Чат с администратором")
 */
class ChatController extends Controller
{
    /**
     * @OA\Get(path="/api/chat/messages", tags={"Chat"}, summary="Сообщения чата", security={{"sanctum":{}}})
     */
    public function index(Request $request)
    {
        $messages = ChatMessage::where('user_id', $request->user()->id)->latest()->limit(50)->get()->reverse()->values();
        return new ChatMessageCollection($messages);
    }

    /**
     * @OA\Post(path="/api/chat/messages", tags={"Chat"}, summary="Отправить сообщение", security={{"sanctum":{}}})
     */
    public function store(Request $request)
    {
        $data = $request->validate(['message' => 'required|string|max:1000']);

        $message = ChatMessage::create([
            'user_id' => $request->user()->id,
            'sender' => 'user',
            'message' => $data['message'],
        ]);

        ChatMessage::create([
            'user_id' => $request->user()->id,
            'sender' => 'admin',
            'message' => 'Администратор получил сообщение. Ответ будет отправлен в этом чате.',
        ]);

        return new ChatMessageResource($message);
    }
}
