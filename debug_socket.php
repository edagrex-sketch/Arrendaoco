<?php
$socket = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
if (!$socket) {
    echo "ERROR: $errno - $errstr\n";
} else {
    echo "Listening on 8080...\n";
    fclose($socket);
}
