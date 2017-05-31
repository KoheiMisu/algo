<?php

set_time_limit(0);
$stdin = fopen("php://stdin", "r");

// fopen に失敗した場合、これを記述しておかないと下の while で無限ループが発生する。
if (!$stdin) {
    exit("[error] STDIN failure.\n");
}

while (true) {
    $searchValue   = '';

    echo "探索する値 :";
    $searchValue = trim(fgets($stdin, 64));

    if (!preg_match('/\d+/', $searchValue)) {
        echo "[error] $searchValue does not number.\n";
        echo "Do you try again? [y/n]: ";

        if ('y' == trim(fgets($stdin, 64))) {
            continue;
        } else {
            exit;
        }
    } else {
        break;
    }

}

fclose($stdin);
