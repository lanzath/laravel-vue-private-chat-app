<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user1_id', 'user2_id'];


    //-------------------------------------------
    // Relations
    //-------------------------------------------

    /**
     * Return session's chats via message.
     *
     * @return HasManyThrough
     */
    public function chats(): HasManyThrough
    {
        return $this->hasManyThrough(Chat::class, Message::class);
    }

    /**
     * Return session's messages.
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
