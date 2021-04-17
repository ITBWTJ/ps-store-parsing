<?php

use PSStoreParsing\DTO\APIStoreParams\{Extensions, GetCategoriesVariables};
use PSStoreParsing\Adapters\APIStore\GetCategoriesFromJsonAdapter;
use PSStoreParsing\Exceptions\ApiStore\GetDataException;
use PSStoreParsing\Repositories\CategoryRepository;
use PSStoreParsing\Services\APIStore\GetCategories;
use PSStoreParsing\Singletones\Container;
use Swoole\Database\PDOPool;

define('__ROOT__', dirname(__DIR__, 2));
define('__SRC__', dirname(__DIR__));

require_once(__ROOT__ . '/vendor/autoload.php');
require_once (__SRC__ . '/bootstrap/bootstrap.php');

$mysql = Container::get(PDOPool::class);

go(function () {
    /** @var CategoryRepository $categoriesRepository */
    $categoriesRepository = Container::get(CategoryRepository::class);
    $allCategories = $categoriesRepository->all();
    $categoriesById = [];

    foreach ($allCategories as $category) {
        $categoriesById[$category->getStoreId()] = $category;
    }

    $HTTPClient = new Swoole\Coroutine\Http\Client($_ENV['PS_STORE_DOMAIN'], 443, true);
    $getCategories = new GetCategories($HTTPClient, Container::get(GetCategoriesVariables::class), Container::get(Extensions::class));

    try {
        $result = $getCategories->get();

        $categoriesAdapter = new GetCategoriesFromJsonAdapter($result);
        $categories = $categoriesAdapter->getData();

        foreach ($categories as $categoryDTO) {
            if (empty($categoriesById[$categoryDTO->getId()])) {
                $model = $categoriesRepository->createFromDTO($categoryDTO);

                $categoriesById[$model->getStoreId()] = $model;
            }
        }

    } catch (GetDataException $e) {
        var_dump('PS STORE EXCEPTION ' . $e->getMessage());
    } catch (\PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException $e) {
        var_dump('PARSE CATEGORIES EXCEPTION ' . $e->getMessage());
    } catch (\Exception $e) {
        var_dump('TIMER GET CATEGORIES EXCEPTION ' . $e->getMessage());
    }
});

Swoole\Timer::tick(5000, function ($timerid, $param) use ($mysql) {
    var_dump('tick');
}, ['params1', 'params2']);
