<?php

/** @var array 整列対象の配列 **/
$target = [5, 4, 7, 6, 8, 3, 1, 2, 9];



/** @var int 並べ替え範囲の先頭要素の添字を入れる変数 **/
$left = 0;

/** @var int 並べ替え範囲の末尾要素の添字を入れる変数 **/
$right = count($target) - 1;

/** @var int 基準値より大きい要素を探すための変数 **/
$i = $left + 1;

/** @var int 基準値より小さい要素を探すための変数 **/
$k = $right;

/** @var int データ交換用の変数 **/
$swap = 0;


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

$swap = $target[$left];
$target[$left] = $target[$k];
$target[$k] = $swap;

/* 整列後の配列 */
var_dump($target);

/*
 * quick_sortを実行していく
 */
