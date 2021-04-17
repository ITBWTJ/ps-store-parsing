<?php

use PSStoreParsing\DTO\APIStoreParams\{Extensions, GetCategoriesVariables, GetProductsByCategoryVariables};
use PSStoreParsing\Adapters\APIStore\GetCategoriesFromJsonAdapter;
use PSStoreParsing\Adapters\APIStore\GetProductsFromJsonAdapter;
use PSStoreParsing\Exceptions\ApiStore\GetCategoriesException;
use PSStoreParsing\Exceptions\ApiStore\GetDataException;
use PSStoreParsing\Repositories\CategoryRepository;
use PSStoreParsing\Repositories\ProductRepository;
use PSStoreParsing\Services\APIStore\GetCategories;
use PSStoreParsing\Services\APIStore\GetProductsByCategory;
use PSStoreParsing\Singletones\Container;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

define('__ROOT__', dirname(__DIR__, 2));
define('__SRC__', dirname(__DIR__));

require_once(__ROOT__ . '/vendor/autoload.php');
require_once (__SRC__ . '/bootstrap/bootstrap.php');

$mysql = Container::get(PDOPool::class);

go(static function () {
    /** @var CategoryRepository $categoriesRepository */
    $categoriesRepository = Container::get(CategoryRepository::class);

    /** @var ProductRepository $productsRepository */
    $productsRepository = Container::get(ProductRepository::class);

    $allCategories = $categoriesRepository->all();
    $allProducts = $productsRepository->all();

    $productsById = [];

    if (!empty($allProducts) && is_array($allProducts)) {
        foreach ($allProducts as $product) {
            $productsById[$product->getStoreId()] = $product;
        }
    }

    $HTTPClient = new Swoole\Coroutine\Http\Client($_ENV['PS_STORE_DOMAIN'], 443, true);

    if (!empty($allCategories) && is_array($allCategories)) {
        foreach ($allCategories as $category) {
            $getProductsByCategoryVariables = new GetProductsByCategoryVariables($category->getLinkTarget(), 1, 0);
            $extensions = new Extensions($_ENV['PS_STORE_API_VERSION'], $_ENV['PS_STORE_API_PRODUCTS_HASH']);
            $getProducts = new GetProductsByCategory($HTTPClient, $getProductsByCategoryVariables, $extensions);

            try {
                $result = $getProducts->get();

                $productsAdapter = new GetProductsFromJsonAdapter($result);
                $productsDTO = $productsAdapter->getData();

                foreach ($productsDTO as $productDTO) {
                    if (empty($productsById[$productDTO->getId()])) {
                        $model = $productsRepository->createFromDTO($productDTO);

                        var_dump($model);
                        $productsById[$model->getStoreId()] = $model;
                    }
                }

            } catch (GetDataException $e) {
                var_dump('PS STORE EXCEPTION ' . $e->getMessage());
            } catch (\PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException $e) {
                var_dump('PARSE PRODUCTS EXCEPTION ' . $e->getMessage());
            } catch (\Exception $e) {
                var_dump('TIMER GET CATEGORIES EXCEPTION ' . $e->getMessage());
            }

        }
    }

});

Swoole\Timer::tick(5000, function ($timerid, $param) use ($mysql) {
    var_dump('tick get products');
}, ['params1', 'params2']);
