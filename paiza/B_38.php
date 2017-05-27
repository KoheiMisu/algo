<?php

/**
 * refs https://paiza.jp/challenges/167/show
 *
 * input (a: 足の数合計, b: 頭の数合計, c: 鶴の足の数, d: 亀の足の数)
 *
 * (x: crane, y: turtle )が一意に定まらない場合は
 * miss を出力
 *
 * cx + dy = a
 * x + y = b
 */


trait Validator
{
    public function checkInputValue(Combination $combination): bool
    {
        switch (true) {
            case (int) $combination->legSum === 0:
            case (int) $combination->headSum === 0:
            case (int) $this->craneLeg === 0 && (int) $this->turtleLeg === 0:
            case $combination->legSum < $this->turtleLeg:
            case $combination->legSum < $this->craneLeg:
            case $combination->legSum < ($this->craneLeg + $this->turtleLeg):
                return false;

            default:
                return true;
        }
    }
}

class Combination
{
    use Validator;

    /**
     * @var int
     */
    private $legSum;

    /**
     * @var int
     */
    private $headSum;

    /**
     * @var int
     */
    private $craneLeg;

    /**
     * @var int
     */
    private $turtleLeg;

    public function __construct()
    {
        list(
            $this->legSum,
            $this->headSum,
            $this->craneLeg,
            $this->turtleLeg
            ) = explode(" ", trim(fgets(STDIN)));
    }

    /**
     * @return void
     */
    public function output()
    {
        // 組み合わせとして考えられない値のとき
        if (!$this->checkInputValue($this)) {
            echo "miss";
            return;
        }

        // 足の数が同じ時
        if ($this->craneLeg === $this->turtleLeg) {

            // 組み合わせとして考えられない値のとき
            if ((int) $this->legSum !== (int) ($this->turtleLeg * $this->headSum)) {
                echo "miss";
                return;
            }

            if ($this->headSum > 1) {
                echo "miss";
                return;
            }
        }

        $turtle = 0;

        while (true) {

            if ($turtle > 100) {
                echo 'miss';
                break;
            }

            $crane = ($this->headSum - $turtle);
            if ((int) $this->legSum === (int)(($crane * $this->craneLeg) + ($turtle * $this->turtleLeg))) {
                echo $crane . ' ' . $turtle;
                break;
            }
            ++$turtle;
        }
    }
}

$combination = new Combination();
$combination->output();