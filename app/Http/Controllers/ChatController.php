<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\ChatRoom;
use Illuminate\Http\Request;
use App\Notifications\MessageNotification;

class ChatController extends Controller
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
     * To get messages of a chat room
     *
     * @param  ChatRoom  $chatRoom
     * @return  mix
     */
    public function index(ChatRoom $chatRoom)
    {
        return $chatRoom->messages;
    }

    /**
     * Show the chat form.
     *
     * @param  User  $toUser
     * @return \Illuminate\View\View
     */
    public function get(User $toUser)
    {
    	$fromUser = auth()->user();
    	$participants = [$toUser->id, $fromUser->id];
    	sort($participants);
    	$participants = implode($participants, ',');

    	$chatRoom = ChatRoom::where('participants', $participants)->first();
    	if(is_null($chatRoom)) {
    		$chatRoom = new ChatRoom;
    		$chatRoom->participants = $participants;
    		$chatRoom->save();
    	}
        $messages = $chatRoom->messages();
    	return view('chat.form', compact('chatRoom', 'messages'));
    }

    /**
     * Send message to receiver
     *
     * @param  ChatRoom  $chatRoom
     * @return  mix
     */
    public function store(ChatRoom $chatRoom)
    {
    	$fromUser = auth()->user();
    	$participants = collect(explode(',', $chatRoom->participants));
    	$participants->forget($participants->search($fromUser->id));
    	$toUser = $participants->first();

    	$message = new Message();
    	$message->chat_room_id = $chatRoom->id;
    	$message->message_from = $fromUser->id;
    	$message->message_to = $toUser;
    	$message->message = request('message');

    	if($message->save()) {
            $message = Message::with('sender')->find($message->id);
            $toUser = User::find($toUser)->notify(new MessageNotification($message));
    		return $message;
    	} else {
    		return response()->json(['error' => 'Some error occured while sending message.']);
    	}
    }
}
