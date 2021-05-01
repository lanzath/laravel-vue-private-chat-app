<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Events\BlockEvent;

class BlockController extends Controller
{
    /**
     * Block user.
     *
     * @param  Session  $session
     * @return JsonResponse
     */
    public function block(Session $session): JsonResponse
    {
        $session->block();

        broadcast(new BlockEvent($session->id, true));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Unblock user.
     *
     * @param  Session  $session
     * @return JsonResponse
     */
    public function unblock(Session $session): JsonResponse
    {
        $session->unblock();

        broadcast(new BlockEvent($session->id, false));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
