<?php

/** @var array 整列対象の配列 **/
$target = [5, 4, 7, 6, 8, 3, 1, 2, 9];

QuickSort($target, 0, count($target)-1);

var_dump($target); //正しい整列結果が出る

/*
 * @param array
 * @param ini
 * @param int
 *
 */
function QuickSort(array &$target, int $left, int $right)
{

    $i = $left + 1;
    $k = $right;

    /*
     * 基準値を値にしてデータを大小に分ける
     */
    while ($i < $k) {

        /**
         * $iを使って、基準値より大きい要素を探す
         */
        while ($target[$i] < $target[$left] && $i < $right) {
            ++$i;
        }

        /**
         * $kを使って、基準値より小さい要素を探す
         */
        while ($target[$k] >= $target[$left] && $k > $left) {
            --$k;
        }

        /**
         * 大きいデータと小さいデータを変換する
         */
        if ($i < $k) {
            $swap = $target[$i];
            $target[$i] = $target[$k];
            $target[$k] = $swap;
        }

    }

    if ($target[$left] > $target[$k]) {
        $swap = $target[$left];
        $target[$left] = $target[$k];
        $target[$k] = $swap;
    }

    if ($left < $k-1) {
        QuickSort($target, $left, $k-1);
    }

    if ($k+1 < $right) {
        QuickSort($target, $k+1, $right);
    }

}
