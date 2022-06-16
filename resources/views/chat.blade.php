@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Chats</div>

                    <div id="data" class="panel-body">
                        @foreach ($messages as $message)
                            <div class="chat-content">
                                <strong>{{ $message->users->name }}: </strong>
                                <span>{{ $message->message }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <form action="{{ route('msg.send') }}" method="POST">
                            @csrf
                            <div>Conversation <input type="text" name="conversation_id"></div>
                            <div>
                                Content
                                <textarea name="message" id="chatInput" rows="5" style="width: 100%"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"
                                                integrity="sha512-9mpsATI0KClwt+xVZfbcf2lJ8IFBAwsubJ6mI3rtULwyM3fBmQFzj0It4tGqxLOGQwGfJdk/G+fANnxfq9/cew=="
                                                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                        {{-- <script>
                            $(function() {
                                let ip_address = '127.0.0.1';
                                let socket_port = '3000';
                                let socket = io.connect('http://localhost:3000');

                                let chatInput = $('#chatInput');

                                chatInput.keypress(function(e) {
                                    let message = $(this).html();
                                    console.log(message);
                                    if (e.which === 13 && !e.shiftKey) {
                                        socket.emit('sendChatToServer', message);
                                        chatInput.html('');
                                        return false;
                                    }
                                });

                                socket.on('sendChatToClient', (message) => {
                                    $('.chat-content').append(`<span>${message}</span>`);
                                });
                            });
                        </script> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
