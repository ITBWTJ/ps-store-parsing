<?php

define('__ROOT__', __DIR__);

require_once(__ROOT__ .'/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable('/var/www/ps-store');
$dotenv->load();

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

$port = $argv[1];
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
$http = new Swoole\HTTP\Server("0.0.0.0", $port);

$mysql = new PDOPool(
    (new PDOConfig())
        ->withHost($_ENV['DB_HOST'])
        ->withPort($_ENV['DB_PORT'])
        // ->withUnixSocket('/tmp/mysql.sock')
        ->withDbName($_ENV['DB_DATABASE'])
        ->withCharset($_ENV['DB_CHARSET'])
        ->withUsername($_ENV['DB_USER'])
        ->withPassword($_ENV['DB_PASSWORD']));

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
