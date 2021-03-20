<?php

$http = new Swoole\HTTP\Server("0.0.0.0", 9500);

$http->on('start', function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9500\n";
});

$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});


$http->start();
