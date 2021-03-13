<?php

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;
const MYSQL_SERVER_HOST = 'db';
const MYSQL_SERVER_PORT = '3306';
const MYSQL_SERVER_DB = 'ps-store';
const MYSQL_SERVER_USER = 'dev';
const MYSQL_SERVER_PWD = 'dev';

$port = $argv[1];
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
$http = new Swoole\HTTP\Server("0.0.0.0", $port);

$mysql = new PDOPool(
    (new PDOConfig())
        ->withHost(MYSQL_SERVER_HOST)
        ->withPort(MYSQL_SERVER_PORT)
        // ->withUnixSocket('/tmp/mysql.sock')
        ->withDbName(MYSQL_SERVER_DB)
        ->withCharset('utf8mb4')
        ->withUsername(MYSQL_SERVER_USER)
        ->withPassword(MYSQL_SERVER_PWD));


$http->on('start', function ($server) use ($port) {
    echo "Swoole http server is started at http://127.0.0.1:{$port}\n";
});

$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($mysql){

    $PDOClient = $mysql->get();
    $statement = $PDOClient->prepare('SELECT * FROM games');

    if (!$statement->execute()) {
        throw new RuntimeException('Execute failed');
    }

    $result = $statement->fetchAll();

    var_dump($result);

    $HTTPClient = new Swoole\Coroutine\Http\Client('jsonplaceholder.typicode.com');
    $result = $HTTPClient->get('/todos/1');
    var_dump($HTTPClient->body);

    var_dump($result);

//    go(function() {
//        Co::sleep(1);
//        echo "Done 1\n";
//    });
//    go(function() {
//        Co::sleep(1);
//        echo "Done 2\n";
//    });

    $data = ['data'];
    $chan = new chan(1);
    $chan->push($data);
    $data = $chan->pop();

    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});


$http->start();
