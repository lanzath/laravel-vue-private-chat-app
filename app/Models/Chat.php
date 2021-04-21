<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message_id', 'session_id', 'user_id', 'read_at', 'type'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $dates = ['read_at'];

    //-------------------------------------------
    // Relations
    //-------------------------------------------

    /**
     * Return chat's message.
     *
     * @return BelongsTo
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
