<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SessionResource;
use Symfony\Component\HttpFoundation\Response;

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
        $session = Session::create([
            'user1_id' => auth()->id(),
            'user2_id' => $request->friend_id,
        ]);

        return response()->json(new SessionResource($session), Response::HTTP_CREATED);
    }
}
