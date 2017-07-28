<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	/**
	 * Messages to chat room relationship
	 *
	 */
    public function sender()
    {
    	return $this->belongsTo(User::class, 'message_from', 'id');
    }

	/**
	 * Messages to chat room relationship
	 *
	 */
    public function chatRoom()
    {
    	return $this->belongsTo(ChatRoom::class);
    }
}
