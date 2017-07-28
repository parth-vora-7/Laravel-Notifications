@extends('layouts.app')

@section('routes')
var fetchChatURL = "{{ route('roomchat', $chatRoom->id) }}";
var postChatURL = "{{ route('message', $chatRoom->id) }}";
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Chat</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST" @submit.prevent="sendMessage">
						{{ csrf_field() }}
						<div id="messages">
							<div v-if="messages.length">
								<message v-for="message in messages" key="message.id" :sender="message.sender.name" :message="message.message" :createdat="message.created_at"></message>
							</div>
							<div v-else>
								<div class="alert alert-warning">No chat yet!</div>
							</div>
						</div>
						<span class="typing" v-if="isTyping"><i><span>@{{ isTyping }}</span> is typing</i></span>
						<hr/>
						<div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
							<label for="message" class="col-md-2 control-label">Message</label>

							<div class="col-md-9">
								<textarea id="message" v-model="message" type="textarea" class="form-control" name="message" required autofocus rows="5" @keyup.enter="sendMessage" @keypress="userIsTyping({{$chatRoom->id}})">{{ old('message') }}</textarea>

								@if ($errors->has('message'))
								<span class="help-block">
									<strong>{{ $errors->first('message') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-8 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Send
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	window.Echo.private('App.User.' + window.Laravel.user.id)
    .notification((notification) => {
        console.log(notification);
    });

    // window.Echo.private(`private-chat-room-{{$chatRoom->id}}`)
    // .listen('PrivateMessageEvent', (e) => {
    //     app.updateChat(e);
    // });

    // window.Echo.private(`typing-room-{{$chatRoom->id}}`)
    // .listenForWhisper('typing', (e) => {
    //     app.isTyping = e.name;
    //     setTimeout(function() {
    //         app.isTyping = '';
    //     }, 1000);
    // });
</script>
@endsection