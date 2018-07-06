<?php

$server = new \swoole_websocket_server("0.0.0.0", 9501);

$server->on('open', function ($server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});

$server->on('message', function ($server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();