<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get a collection of users.
     *
     * @return AnonymousResourceCollection
     */
    public function getUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(User::where('id', '!=', auth()->id())->get());
    }

    /**
     * Lists user's friends.
     *
     * @return JsonResponse
     */
    public function getFriends(): JsonResponse
    {
        return response()->json($this->getUsers(), Response::HTTP_OK);
    }
}
