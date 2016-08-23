<?php

/**
 * 整列するデータ
 */
$data = [5, 4, 7, 6, 8, 3, 2, 1, 9];

/**
 * 並べ替え範囲の先頭要素の添字を入れる変数
 */
$left = 0;

/**
 * 並べ替え範囲の末尾要素の添字を入れる変数
 */
$right = 8;

function quickSort($data, $left, $right) {
    /**
     * 基準値より大きい要素を探すための変数
     */
    $i = $left + 1;

    /**
     * 基準値より小さい要素を探すための変数
     */
    $k = $right;

    /**
     * 交換用の変数
     */
    $swap = '';

    /**
     * 交換処理を繰り返す
     */
    while ($i < $k) {

        /**
         * 変数 $i を使って基準値より大きい要素を探す
         */
        while ($data[$i] < $data[$left] && $i < $right) {
            ++$i;
        }

        /**
         * 変数 $k を使って基準値より小さい要素を探す
         */
         while ($data[$k] >= $data[$left] && $k > $left) {
             --$k;
         }

         /**
          * i が k 以上になったら交換を実行しない
          * 大きいデータと小さいデータを変換する
          */
         if ($i < $k) {
             $swap = $data[$i];

             $data[$i] = $data[$k];

             $data[$k] = $swap;
         }
    }

    /**
     * 基準値を大小データの真ん中に移動
     */
     if ($data[$left] > $data[$k]) {
         $swap = $data[$left];

         $data[$left] = $data[$k];

         $data[$k] = $swap;
     }


     if ($left < $k-1) {
        echo "a, ".$left.":".($k-1)."\n";
        quickSort($data, $left, $k-1);
     }

     if ($k+1 < $right) {
         echo "b, ".($k+1).":".$right."\n";
         quickSort($data, $k+1, $right);
     }
}

quickSort($data, 0, 8);

// var_dump($data);
