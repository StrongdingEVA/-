var app = require('http').createServer(handler);
var io = require('socket.io')(app);

app.listen(6001, function() {
    console.log('Server is running!');
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function(socket) {
    console.log('connected');
});



var socket = io.connect('http://localhost:6001');
socket.on('connection', function (data) {
    console.log(data);
});
socket.on('test-channel:App\\Events\\SomeEvent', function(message){ //接收广播
    console.log(message);
});
console.log(socket);