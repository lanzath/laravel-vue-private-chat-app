<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SessionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Session;

class SessionController extends Controller
{
    /**
     * Create a new session between 2 users.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $newSession = Session::create([
            'user1_id' => auth()->id(),
            'user2_id' => $request->friend_id,
        ]);

        $session = new SessionResource($newSession);

        broadcast(new SessionEvent($session, auth()->id()));

        return response()->json($session, Response::HTTP_CREATED);
    }
}
