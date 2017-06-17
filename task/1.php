<?php

/**
 * php version 7.1
 *
 * 変数int xを引数として渡されると、xの約数を全て足し算した結果を返すプログラムを作成せよ。
 *   例：x=12の場合、約数は1,2,3,4,6,12、なので28を返す。
 *
 * 約数の総和公式を用いる
 */

/**
 * 指定された数までの素数を返す
 */
trait PrimeNumber
{
    /**
     * @param int $limit
     * @throws Exception 不正な値が渡されたとき
     * @return array
     */
    public function eratosthenes(int $limit): array
    {
        if ($limit < 2) {
            throw new Exception('invalid number', $limit);
        }

        if ($limit === 2) {
            return [2];
        }

        $baseNums = range(2, $limit);

        $maxPrimeNumber = sqrt($limit);

        /**
         * 使用済みの数字をキャッシュする
         */
        $stack = [];

        /**
         * 配列から順次削ぎ落としていく
         */
        for ($i = 2; $i < $maxPrimeNumber; $i++) {
            $filterFlug = true;

            /**
             * 一度フィルターに使った数字の倍数で
             * フィルターをかけないようにする
             */
            foreach ($stack as $usedNum) {
                if ($i % $usedNum === 0) {
                    $filterFlug = false;
                    break;
                }
            }

            /**
             * baseNumsの数字をふるい落としていく
             */
            if ($filterFlug) {
                $stack[] = $i;

                $baseNums = array_filter($baseNums, function ($v) use ($i, $stack) {
                    /**
                     * $stackに格納されている数字はフィルタリングを行わない。
                     */
                    if (in_array($v, $stack)) {
                        return true;
                    }

                    return !($v % $i === 0);
                });
            }
        }

        return array_values($baseNums);
    }
}

/**
 * 指定された数に対して素因数分解を行う
 */
trait Factoring
{
    use PrimeNumber;

    /**
     * [
     *   [num => index]
     *   .
     *   .
     * ]
     *
     * の形で配列を返す
     *
     * @param int $input
     * @return array
     */
    public function factoring(int $input): array
    {
        if ($input === 1) {
            return [$input => $input];
        }

        $result = [];

        $primeNumbers = $this->eratosthenes($input);

        /**
         * 入力値を素数で割り続ける
         * 入力値が1より小さくなったら処理を抜ける
         */
        foreach ($primeNumbers as $primeNumber) {
            while ($input % $primeNumber === 0) {
                $result[] = $primeNumber;
                $input = ($input / $primeNumber);
            }

            if ($input < 1) {
                break;
            }
        }

        return array_count_values($result);
    }
}

/**
 * Class _Divisor
 *
 * 約数の和を返す.
 */
class Divisor
{
    use Factoring;

    /**
     * 約数の総和公式を用いる
     *
     * ex.) 45
     * 3^2 * 5 → (3^0 + 3^1 + 3^2)(5^0 + 5^1) = 78
     *
     * @param int $input
     * @return int
     */
    public function sum(int $input): int
    {
        $sumResult = [];
        $factoringResult = $this->factoring($input);

        if ($factoringResult === [1 => 1]) {
            return 1;
        }

        foreach ($factoringResult as $num => $index) {
            $sum = 0;
            for ($i = 0; $i <= $index; $i++) {
                $sum += pow($num, $i);
            }
            $sumResult[] = $sum;
        }

        return array_product($sumResult);
    }
}

$input = (int) trim(fgets(STDIN));
$divisor = new Divisor();
echo $divisor->sum($input) . PHP_EOL;
