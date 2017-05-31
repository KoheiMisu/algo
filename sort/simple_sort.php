<?php

/**
 * 並び替え用の配列
 */
// $data = ['45', '66', '24', '34', '55'];
$data = [];
for ($i=0; $i < 10000; $i++) {
    $data[] = rand(0, 100000);
}

$loopCount = 0;

$tmp = '';

$loopLimit = count($data);

$start = microtime(true);

while($loopLimit > $loopCount) {

    $indexMin = searchMinKey($loopCount, $data);

    $tmp = $data[$loopCount];
    $data[$loopCount] = $data[$indexMin];
    $data[$indexMin] = $tmp;
    ++$loopCount;
}

$end = microtime(true);
echo "処理時間：" . ($end - $start) . "秒"."\n";

function searchMinKey($loopCount, $data)
{
    $indexMin = $loopCount;

    for ($i=$loopCount; $i < count($data); $i++) {
        if ($data[$indexMin] >= $data[$i]) {
            $indexMin = $i;
        }
    }

    return $indexMin;
}

// var_dump($data);
