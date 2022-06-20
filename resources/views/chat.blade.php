@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-4">
                <button class="btn btn-primary">Room: {{ $id }}</button>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <h2 class="panel-heading">Chats</h2>

                    <div id="data" class="panel-body">
                        @foreach ($messages as $message)
                            <div id="chat-content">
                                <strong>{{ $message->users->name }}: </strong>
                                <span>{{ $message->message }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <form action="{{ route('msg.send', $id) }}" id="form" method="POST">
                            @csrf
                            <div>
                                Content
                                <textarea name="message" id="chatMsg" rows="5" style="width: 100%"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"
                                                integrity="sha512-9mpsATI0KClwt+xVZfbcf2lJ8IFBAwsubJ6mI3rtULwyM3fBmQFzj0It4tGqxLOGQwGfJdk/G+fANnxfq9/cew=="
                                                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                        <script>
                            var socket = io('localhost:3000');
                            var form = document.getElementById('form');
                            var input = document.getElementById('chatInput');
                            var msg = document.getElementById('chatMsg');
                            var data = document.getElementById('data');

                            form.addEventListener('submit', function(e) {
                                // e.preventDefault();
                                socket.emit('chat message', msg.value, '{{Auth::user()->name}}');
                            });

                            socket.on('chat message', function(msg, name) {
                                data.innerHTML = data.innerHTML + `<div id="chat-content">
                                                        <strong>${name}: </strong>
                                                        <span>${msg}</span>
                                                    </div>`;
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
