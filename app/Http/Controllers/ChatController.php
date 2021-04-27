<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Events\PrivateChatEvent;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ChatResource;
use App\Events\MsgReadEvent;

class ChatController extends Controller
{
    /**
     * Handle the message sent and saves to storage.
     *
     * @param  Session  $session
     * @param  Request  $request
     * @return JsonResponse
     */
    public function send(Session $session, Request $request): JsonResponse
    {
        // Create message through session relationship
        $message = $session->messages()->create(['content' => $request->content]);

        $chat = $message->createForSend($session->id);

        $message->createForReceive($session->id, $request->to_user);

        broadcast(new PrivateChatEvent($message->content, $chat));

        // Returns the sent message id to front-end
        return response()->json($chat->id, Response::HTTP_CREATED);
    }

    /**
     * Lists all messages from current chat.
     *
     * @param  Session  $session
     * @return JsonResponse
     */
    public function chats(Session $session): JsonResponse
    {
        $chats = $session->chats->where('user_id', auth()->id());

        return response()->json(ChatResource::collection($chats), Response::HTTP_OK);
    }

    /**
     * Stores in database the datetime that message have been read.
     *
     * @param  Session  $session
     * @return void
     */
    public function read(Session $session): void
    {
        $chats = $session->chats->where('read_at', null)
            ->where('type', 'sender')
            ->where('user_id', '!=', auth()->id());

        foreach ($chats as $chat) {
            $chat->update(['read_at' => now()]);

            // Event broadcast for each read message
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->session_id));
        }
    }

    /**
     * Delete messages from requested user.
     *
     * @param  Session  $session
     * @return JsonResponse
     */
    public function clear(Session $session): JsonResponse
    {
        $session->deleteChats();

        $session->chats->count() == 0 ? $session->deleteMessages() : '';

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
