<?php


namespace PSStoreParsing\Singletones;


class Container
{
    private static \DI\Container $container;

    private function __construct()
    {
    }

//    private function __wakeup()
//    {
//    }

    private function __cone()
    {
    }

    public static function setContainer(\DI\Container $container): void
    {
        self::$container = $container;
    }

    public static function get(string $name): mixed
    {
        $container = self::getInstance();

        return $container->get($name);
    }

    public static function has(string $name): bool
    {
        $container = self::getInstance();

        return $container->has($name);
    }

    private static function getInstance(): \DI\Container
    {
        if (empty(self::$container)) {
            self::$container = new \DI\Container();
        }

        return self::$container;
    }
}
