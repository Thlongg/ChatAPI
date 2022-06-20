const express = require('express');

const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});

io.on('connection', (socket) => {
    console.log('connection');

    socket.on('chat message', (msg,userSend) => {
        io.emit('chat message', msg, userSend);
    });
    
    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});