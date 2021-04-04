<?php

use Psr\Container\ContainerInterface;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;
use function DI\create;
use function DI\get;

return [
    // MySQL environments
    'db.host' => $_ENV['DB_HOST'],
    'db.port' => $_ENV['DB_PORT'],
    'db.user' => $_ENV['DB_USER'],
    'db.password' => $_ENV['DB_PASSWORD'],
    'db.name' => $_ENV['DB_DATABASE'],

    // PS Store environments
    'ps-store.domain' => $_ENV['PS_STORE_DOMAIN'],
    'ps-store.api-client-id' => $_ENV['PS_STORE_API_CLIENT_ID'],
    'ps-store.api-alias' => $_ENV['PS_STORE_API_ALIAS'],
    'ps-store.api-version' => (int)$_ENV['PS_STORE_API_VERSION'],
    'ps-store.api-hash' => $_ENV['PS_STORE_API_HASH'],


    PDOPool::class => function (PDOConfig $PDOConfig) {
        return new PDOPool($PDOConfig);
    },
    PDOConfig::class => function (ContainerInterface $container) {
        return (new PDOConfig())
        ->withHost($container->get('db.host'))
            ->withPort($container->get('db.port'))
            ->withDbName($container->get('db.name'))
//            ->withCharset($_ENV['DB_CHARSET'])
            ->withUsername($container->get('db.user'))
            ->withPassword($container->get('db.password'));
    }
];
