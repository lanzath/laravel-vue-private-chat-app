<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\ChatResource;

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
        $message = $session->messages()->create(['content' => $request->content]);

        $message->createForSend($session->id);

        $message->createForReceive($session->id, $request->to_user);

        return response()->json($message, Response::HTTP_CREATED);
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
}