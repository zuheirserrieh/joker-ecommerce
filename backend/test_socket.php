<?php
$port = 8888;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket) {
    echo "Socket created\n";
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    if (socket_bind($socket, '127.0.0.1', $port)) {
        echo "Bind successful on port $port\n";
        socket_close($socket);
    } else {
        echo "Bind failed: " . socket_strerror(socket_last_error()) . "\n";
    }
} else {
    echo "Socket creation failed: " . socket_strerror(socket_last_error()) . "\n";
}
