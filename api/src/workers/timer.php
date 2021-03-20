<?php

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

define('__ROOT__', dirname(__DIR__, 2));
define('__SRC__', dirname(__DIR__));

require_once(__ROOT__ . '/vendor/autoload.php');
var_dump(__ROOT__,__DIR__, dirname(__ROOT__));
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
$dotenv = Dotenv\Dotenv::createImmutable(__ROOT__);
$dotenv->load();

$mysql = new PDOPool(
    (new PDOConfig())
        ->withHost($_ENV['DB_HOST'])
        ->withPort($_ENV['DB_PORT'])
        // ->withUnixSocket('/tmp/mysql.sock')
        ->withDbName($_ENV['DB_DATABASE'])
        ->withCharset($_ENV['DB_CHARSET'])
        ->withUsername($_ENV['DB_USER'])
        ->withPassword($_ENV['DB_PASSWORD']));


go(function () {
//    $urlVariablesData = [
//        'clientId' => '39c33e4d-7a96-11ea-acb6-06293b18fe04',
//        'alias' => 'deals',
//    ];
//
//    $urlExtensionsData = [
//        'persistedQuery' => [
//            'version' => 1,
//            'sha256Hash' => '715de527ebee664cfca0e06c3ad3bdfc31628826cf4f78b0d24468719ed8abb3',
//        ]
//    ];
//    $variables = json_encode($urlVariablesData);
//    $extensions = json_encode($urlExtensionsData);
//    $operationName = 'getExperience';
//
//    $urlData = [
//        'operationName' => $operationName,
//        'variables' => $variables,
//        'extensions' => $extensions,
//    ];
//
//    var_dump($_ENV['PS_STORE_DOMAIN']);
//    $HTTPClient = new Swoole\Coroutine\Http\Client($_ENV['PS_STORE_DOMAIN'], 443, true);
//    $headers = [
//        'accept-language' => 'ru-UA',
//        'x-psn-store-locale-override' => 'ru-UA',
//    ];
//    $HTTPClient->setHeaders($headers);
//
//    $path = '/api/graphql/v1//op?' . http_build_query($urlData);
//    var_dump($path);
////    $HTTPClient->ssl = true;
//    $result = $HTTPClient->get($path);

//    if ($result) {
//        $responseData = json_decode($HTTPClient->body, true);
//        var_dump($responseData);
//    }

    $HTTPClient = new Swoole\Coroutine\Http\Client($_ENV['PS_STORE_DOMAIN'], 443, true);
    $variables = new \PSStoreParsing\DTO\APIStoreParams\Variables($_ENV['PS_STORE_API_CLIENT_ID'],$_ENV['PS_STORE_API_ALIAS']);
    $extensions = new \PSStoreParsing\DTO\APIStoreParams\Extensions($_ENV['PS_STORE_API_VERSION'],$_ENV['PS_STORE_API_HASH']);
    $getCategories = new \PSStoreParsing\Services\APIStore\GetCategories($HTTPClient, $variables, $extensions);

    try {
        $result = $getCategories->get();
        var_dump($result);
    } catch (\PSStoreParsing\Exceptions\ApiStore\GetCategoriesException $e) {
        var_dump('PS STORE EXCEPTION ' . $e->getMessage());
    } catch (\Exception $e) {
        var_dump('PS STORE EXCEPTION ' . $e->getMessage());
    }

});

Swoole\Timer::tick(5000, function ($timerid, $param) use ($mysql) {

}, ['params1', 'params2']);
