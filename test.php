<?php

$test = [];

$test[] = [
    'a' => [1, 2, 3, 4, 5]
];

$test[] = [
    'b' => [1, 2, 3, 4, 5]
];

foreach ($test as $t) {
    foreach ($t as $v) {
        foreach ($v as $key => $value) {
            next($v);
            echo $value . "\n";
            echo current($v)."\n\n";
        }
    }
}
