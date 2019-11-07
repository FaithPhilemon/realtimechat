var socket = require('socket.io');
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = socket.listen(server);
var port = process.env.PORT || 3000;
server.listen(port, function () {
    console.log('Server listening at port %d', port);
});
io.on('connection', function (socket) {
    socket.on('enter_user', function (data) {
        io.sockets.emit('enter_user', {
            user_id: data.user_id,
            username: data.username,
        });
    });
    socket.on('logout_user', function (data) {
        io.sockets.emit('logout_user', {
            user_id: data.user_id,
        });
    });
    socket.on('send_msg', function (data) {
        io.sockets.emit('send_msg', {
            user_id: data.user_id,
            chat_message: data.chat_message,
            chat_image: data.chat_image,
            created_at: data.created_at,
            chat_id: data.chat_id,
            username: data.username,
        });
    });
});