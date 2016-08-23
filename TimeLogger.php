<?php

class TimeLogger
{
    public function timeLogger(callable $callback)
    {
        $start = microtime(true);
        call_user_func($callback);
        $end = microtime(true);
        echo "処理時間：" . ($end - $start) . "秒";
    }

    $linear = function () {
        foreach(range(0, 10000) as $number) {
            echo $number;
        }
    };
}
