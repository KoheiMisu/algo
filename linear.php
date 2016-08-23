<?php

function timeLogger(callable $callback)
{
    $start = microtime(true);
    call_user_func($callback);
    $end = microtime(true);
    echo "処理時間：" . ($end - $start) . "秒"."\n";
}

$target = 888888;

$linear = function () use ($target) {
    foreach(range(0, 100000) as $number) {
        if ($number === $target) {
            echo "found"."\n";
            break;
        }
    }
};

$binary = function () use ($target) {
    $head = 0;
    $tail = 100000;

    foreach(range(0, 100000) as $number) {
        $center = floor(($head + $tail) / 2);

        if ($target === $center) {
            echo "found"."\n";
            break;
        }

        if ($center > $target) {
            $tail = $center - 1;
        } else {
            $head = $center + 1;
        }

    }
};

timeLogger($linear);
echo "\n\n";
timeLogger($binary);
