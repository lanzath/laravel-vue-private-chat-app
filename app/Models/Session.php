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

    /**
     * Delete chats from related table.
     *
     * @return void
     */
    public function deleteChats(): void
    {
        $this->chats()->where('user_id', auth()->id())->delete();
    }

    /**
     * Delete messages from related table.
     *
     * @return void
     */
    public function deleteMessages(): void
    {
        $this->messages()->delete();
    }

    /**
     * Set block and blocked_by fields on Database.
     *
     * @return Session
     */
    public function block(): Session
    {
        $this->block = true;
        $this->blocked_by = auth()->id();
        $this->save();

        return $this;
    }

    /**
     * Unset block and blocked_by fields on Database.
     *
     * @return Session
     */
    public function unblock(): Session
    {
        $this->block = false;
        $this->blocked_by = null;
        $this->save();

        return $this;
    }
}
