<?php

use DI\ContainerBuilder;
use PSStoreParsing\Singletones\Container;

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

$dotenv = Dotenv\Dotenv::createImmutable(__ROOT__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__SRC__ . '/bootstrap/container.php');
$containerBuilder->enableCompilation(__SRC__ . '/cache/container');
$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(true);

$container = $containerBuilder->build();

Container::setContainer($container);
