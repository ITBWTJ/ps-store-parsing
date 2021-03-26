<?php

use PSStoreParsing\DTO\APIStoreParams\{Extensions, Variables};
use PSStoreParsing\Adapters\APIStore\GetCategoriesFromJsonAdapter;
use PSStoreParsing\Exceptions\ApiStore\GetCategoriesException;
use PSStoreParsing\Services\APIStore\GetCategories;
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
    $HTTPClient = new Swoole\Coroutine\Http\Client($_ENV['PS_STORE_DOMAIN'], 443, true);
    $variables = new Variables($_ENV['PS_STORE_API_CLIENT_ID'],$_ENV['PS_STORE_API_ALIAS']);
    $extensions = new Extensions($_ENV['PS_STORE_API_VERSION'],$_ENV['PS_STORE_API_HASH']);
    $getCategories = new GetCategories($HTTPClient, $variables, $extensions);

    try {
        $result = $getCategories->get();
        var_dump($result);
        $categoriesAdapter = new GetCategoriesFromJsonAdapter($result);
        $categories = $categoriesAdapter->getCategories();
        var_dump($categories);
    } catch (GetCategoriesException $e) {
        var_dump('PS STORE EXCEPTION ' . $e->getMessage());
    } catch (\PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException $e) {
        var_dump('PARSE CATEGORIES EXCEPTION ' . $e->getMessage());
    } catch (\Exception $e) {
        var_dump('TIMER GET CATEGORIES EXCEPTION ' . $e->getMessage());
    }
});

Swoole\Timer::tick(5000, function ($timerid, $param) use ($mysql) {

}, ['params1', 'params2']);
