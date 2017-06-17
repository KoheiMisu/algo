<?php

/**
 * task1
 *
 * this program is written by php7.1
 *
 * Divisorクラスに約数を格納して総和を出す
 */

trait Number
{
    /**
     * @var int
     */
    private $number;

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
    }
}

class Divisor
{
    use Number;

    /**
     * @var array
     */
    private $divisor;

    /**
     * Divisor constructor.
     * @param int $input
     */
    public function __construct(int $input)
    {
        $this->number = $input;
        $this->setDivisor();
    }

    /**
     * @return void
     */
    public function setDivisor(): void
    {
        if ($this->number === 1) {
            $this->divisor[] = 1;
            return;
        }

        /**
         * 初期値
         */
        $this->divisor = [1, $this->number];

        /**
         * floorは、floatで返してくるのでキャストする
         */
        $middleNumber = (int) floor(($this->number / 2));

        if ($middleNumber === 1) {
            return;
        }

        /**
         * 約数が複数期待できるとき
         */
        for ($i = 2; $i <= $middleNumber; $i++) {
            if ($this->number % $i === 0) {
                $this->divisor[] = $i;
            }
        }
    }

    /**
     * @return int
     */
    public function getSum(): int
    {
        return array_sum($this->divisor);
    }
}

$input = (int) trim(fgets(STDIN));

$divisor = new Divisor($input);

echo $divisor->getSum() . PHP_EOL;
