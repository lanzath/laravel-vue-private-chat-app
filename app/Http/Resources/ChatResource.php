<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message['content'],
            'type' => $this->type,
            'sent_at' => $this->created_at->diffForHumans(),
            'read_at' => $this->read_at ? $this->read_at->diffForHumans() : '',
        ];
    }
}
