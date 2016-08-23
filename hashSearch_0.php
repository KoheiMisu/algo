<?php
/**
 * データを格納する処理
 */

/* 元データ */
$data = ['12', '25', '36', '20', '30', '8', '42'];

/* ハッシュ関数を通してデータ格納した配列 */
$hashArray = [];

/**
 * ハッシュ関数
 * 値に対してハッシュのキーを計算して返す
 */
$hash = function ($value) {
    return $value % 11;
};

/**
 * dataをハッシュ関数を通してhashArrayに格納する
 */
foreach ($data as $value) {
    $hashKey = $hash($value);

    /**
     * hashArrayにhashKeyに対応する値が入っていなければ値を格納する
     */
    if (!isset($hashArray[$hashKey])) {
        $hashArray[$hashKey] = $value;
    }
}

echo "\n========データ格納後の配列========\n\n";
var_dump($hashArray);
