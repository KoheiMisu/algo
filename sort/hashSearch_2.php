<?php

require_once('./hashSearch_1.php');

echo "\n========データ検索========\n";

//探索値: searchValueを対話型で取得
require_once('./intaractive.php');

/**
 * データを探索する処理
 */

//探索する値に対応するキー
$searchKey = $hash($searchValue);

/**
 * searchKeyがそもそも存在しているかチェック
 */
if (!isset($hashArray[$searchKey])) {
    die("対応するハッシュキーがありません\n");
}

if ($hashArray[$searchKey] === $searchValue) {
    die($searchValue."は".$searchKey."番目の要素に格納されています\n");
}

/**
 * $searchKeyに対応したhashArrayの値とぶつかるまで探索
 */
while ($hashArray[$searchKey] !== $searchValue) {
    ++$searchKey;
    if ($hashArray[$searchKey] === $searchValue) {
        die($searchValue."は".$searchKey."番目の要素に格納されています\n");
    }
}
