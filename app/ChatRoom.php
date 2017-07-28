<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
	/**
	 * Chat room to messages relationship
	 *
	 */
    public function messages()
    {
    	return $this->hasMany(Message::class)->with('sender');
    }
}
