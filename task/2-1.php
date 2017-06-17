<?php

/**
 * php version 7.1
 *
 * 素因数分解を再帰的に行う関数を作成せよ
 */

/**
 * @param  int    $input
 * @param  int    $divideNum $inputを割る値
 * @param  array  $stack     既に使った値
 * @param  array  $result    素因数分解結果
 *
 * @return void
 */
function factoring(int &$input, int &$divideNum, array &$stack, array &$result)
{
    // 終了条件
    if ($input <= 1) {
        // $input = 1の場合はすぐに返却
        if (count($result) === 0) {
            return [$input];
        }

        return;
    }

    while ($input % $divideNum === 0) {
        $result[] = $divideNum;
        $input = ($input / $divideNum);
    }

    $stack[] = $divideNum;
    ++$divideNum;

    factoring($input, $divideNum, $stack, $result);
}

$input = (int) trim(fgets(STDIN));
$result = [];
$stack = [];

factoring($input, $divideNum = 2, $stack, $result);

echo 'factoring result is ' . implode(',', $result) . PHP_EOL;
