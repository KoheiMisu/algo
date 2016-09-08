<?php

/**
 * 並び替え用の配列
 */
// $data = ['45', '66', '24', '34', '55'];
$data = [];
for ($i=0; $i < 10000; $i++) {
    $data[] = rand(0, 100000);
}

/**
 * 並び替え結果格納用配列
 */
$result = [];

$loopCount = 0;

$tmp = '';

$loopLimit = count($data);

$start = microtime(true);

while($loopLimit > $loopCount) {

    $indexMin = searchMinKey($data);

    $result[] = $data[$indexMin];
    unset($data[$indexMin]);
    ++$loopCount;
}

$end = microtime(true);
echo "処理時間：" . ($end - $start) . "秒"."\n";

function searchMinKey($data)
{
    //先頭要素のキーを取得する
    $indexMin = array_search(current($data), $data);

    foreach ($data as $key => $value) {
        if ($data[$indexMin] >= $data[$key]) {
            $indexMin = $key;
        }
    }

    return $indexMin;
}

// var_dump($result);
