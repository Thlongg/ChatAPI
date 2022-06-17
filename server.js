const express = require('express');

const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});

io.on('connection', (socket) => {
    console.log('connection');

    socket.on('chat message', (msg) => {
        io.emit('chat message', {msg});
      });

    socket.on('conversation', (input) => {
        io.emit('conversation', {input});
    });
        
    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});