<?php

/**
 * 10
 *
 * 0 255 123 12 2 4 12 4 55 2
 *
 * 5      0   3
 * dest src size
 *
 * 0 255 123 12 2 0 255 123 55 2
 * 0 255 123 12 2 0 255  55 2
 *
 * src(0)からsrc+size-1 (2)の位置の値を vのdestから dest+size-1 (7)の位置までコピー
 */

$arraySize = trim(fgets(STDIN));
$v = explode(" ", trim(fgets(STDIN)));

list(
    $dest,
    $src,
    $size
    ) = explode(" ", trim(fgets(STDIN)));

/**
 * @param array $v
 * @param int $dest
 * @param int $src
 * @param int $size
 *
 * @return string
 */
function memcpy(array $v, $dest, $src, $size)
{
    $result = implode(" ", $v);
    $arraySize = count($v);

    $copyDest = $src + $size - 1;
    $overrideDest = $dest + $size - 1;

    if (
        $dest < 0 ||
        $copyDest < 0 ||
        $overrideDest < 0 ||
        ($overrideDest - $dest) <= 0
    ) {
        return $result;
    }

    $copySrcArray = array_slice($v , $src, ($copyDest+1));

    /**
     * 元の配列からコピーできる値がないとき
     */
    if (count($copySrcArray) === 0) {
        return $result;
    }

    $replacementKeys = range($dest, $overrideDest);

    /**
     * 追加で利用できるメモリのサイズは、O(1)
     */
    if (array_slice($replacementKeys, -1)[0] > $arraySize + 1) {
        return $result;
    }

    /**
     * 入れ替えの実行
     *
     * 配列の対応するキーの値に対してコピー元の配列を先頭から取り出して当て込む
     * (array_shiftは元の配列を破壊して取り出す)
     */
    foreach ($replacementKeys as $index => $replacementKey) {
        $v[$replacementKey] = array_shift($copySrcArray);
    }

    $result = implode(" ", $v);

    return $result;
}

echo memcpy($v, $dest, $src, $size) . PHP_EOL;

