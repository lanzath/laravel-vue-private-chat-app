<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'session_id'];


    /**
     * Return chats relationship.
     *
     * @return HasMany
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Store data for message's sender on Chat table.
     *
     * @param  int  $session_id
     * @return Model
     */
    public function createForSend(int $session_id): Model
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => auth()->id(),
            'type' => 'sender'
        ]);
    }

    /**
     * Store data for message's receiver on Chat table.
     *
     * @param  int  $session_id
     * @param  int  $to_user
     * @return Model
     */
    public function createForReceive(int $session_id, int $to_user): Model
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => $to_user,
            'type' => 'receiver'
        ]);
    }
}
