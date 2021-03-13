<?php


function run($timerid, $param) {
    var_dump($timerid);
    var_dump($param);
}
Swoole\Timer::tick(1000, "run", ["param1", "param2"]);
