<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Session;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence channel to return who's connected to this channel.
Broadcast::channel('Chat', function ($user) {
    return $user;
});

// Private channel for private chat event listening
Broadcast::channel('Chat.{session}', function ($user, Session $session) {
    // Only authorize the 2 users in a chat
    return $user->id == $session->user1_id || $user->id == $session->user2_id;
});