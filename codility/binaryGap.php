<?php

trait Validator
{
    /**
     * @param int $value
     * @param int $min
     * @param int $max
     * @return bool
     * @throws Exception
     */
    public function checkValueRange(int $value, int $min, int $max): bool
    {
        if ($value >= $min && $max >= $value) {
            return true;
        }

        $range = $min . ' ~ ' . $max;
        throw new Exception('value is invalid. now :' . $value . ', range:' . $range);
    }
}

class Number
{
    use Validator;

    const MIN = 1;
    const MAX = 2147483647;

    /**
     * @var int
     */
    private $value;

    /**
     * Number constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->checkValueRange($value, self::MIN, self::MAX);

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getBinaryValue(): string
    {
        return decbin($this->value);
    }
}

class NumberManager
{
    /**
     * @var Number
     */
    private $number;

    /**
     * @var Gap
     */
    private $gap;

    /**
     * NumberManager constructor.
     * @param Number $number
     */
    public function __construct(Number $number)
    {
        $this->number = $number;
        $this->gap = new Gap();
    }

    /**
     * @return int
     */
    public function calculateBinaryGap(): int
    {
        $binaryNumber = $this->number->getBinaryValue();
        $splitResult = str_split($binaryNumber);

        // 末尾の0は取り除く
        while (array_pop($splitResult) == 0) {}

        // 上記処理で１が取り除かれてしまうので詰め直す
        array_push($splitResult, (string) 1);

        $gap = 0;
        $isGap = false;

        foreach ($splitResult as $val) {

            if ($val == 1) {
                $isGap = true;
                $this->gap->setGap($gap);

                //初期化
                $gap = 0;
                continue;
            }

            if ($isGap && $val == 0) {
                ++$gap;
            }
        }

        return $this->gap->getValue();
    }
}

class Gap
{
    /**
     * @var int
     */
    private $result = 0;

    /**
     * @return int|bool
     */
    public function getValue()
    {
        return $this->result;
    }

    /**
     * 事前に詰められてた値と比較して小さければresultに格納するようにする
     *
     * @param int $gap
     */
    public function setGap(int $gap)
    {
        if (!$this->result) {
            $this->result = $gap;
            return;
        }

        if ($gap > $this->result) {
            $this->result = $gap;
        }
    }
}

/**
 * @param mixed $N
 * @return int
 */
function solution($N): int
{
    $number = new Number($N);
    $numberManager = new NumberManager($number);

    return $numberManager->calculateBinaryGap();
}

var_dump(solution(328));

/**
 * Binary Gap
 *
 * 正の整数N内のバイナリギャップは、Nのバイナリ表現の両端で1で囲まれた連続するゼロの任意の最大シーケンスです。

例えば、番号9はバイナリー表現1001を持ち、長さ2のバイナリー・ギャップを含んでいます。
番号529は2進表現1000010001を持ち、長さ4と長さ3の2つのバイナリー・ギャップを含んでいます。
1つのバイナリギャップの長さは1である。数字15はバイナリ表現1111を有し、バイナリギャップを持たない。

関数を書く：

関数の解（$ N）;

正の整数Nが与えられると、その最長のバイナリギャップの長さを返します。 Nにバイナリギャップが含まれていない場合、この関数は0を返します。

例えば、N = 1041であれば、関数は5を返します.Nはバイナリ表現10000010001を持ち、その最長バイナリギャップは長さ5です。

と仮定する：

Nは[1..2,147,483,647]の範囲内の整数である。
複雑：

予想される最悪ケースの時間複雑度はO（log（N））です。
期待される最悪ケースの空間複雑度はO（1）である。
 */
