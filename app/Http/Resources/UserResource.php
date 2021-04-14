<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Session;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'online' => false,
            'session' => $this->sessionDetails($this->id)
        ];
    }

    /**
     * Get session details between 2 users.
     *
     * @param  int  $id
     * @return array
     */
    private function sessionDetails(int $id)
    {
        $session = Session::whereIn('user1_id', [auth()->id(), $id])
            ->whereIn('user2_id', [auth()->id(), $id])
            ->first();

        return new SessionResource($session);
    }
}
