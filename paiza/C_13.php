<?php

/**
 * refs https://paiza.jp/challenges/177/show
 */

// inputの処理

// + で区切る

// 書く要素を整数に変換

// 足し算の処理


class Code
{
    /**
     * @var int
     */
    private $value;

    public function __construct(string $value)
    {
        $translated = $this->decode($value);
        $this->setValue($translated);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value)
    {
        $this->value = $value;
    }

    private function decode(string $value): int
    {
        $result = 0;

        $str = str_split($value);

        foreach ($str as $s) {
            if ($s === '<') {
                $result += 10;
                continue;
            }

            if ($s === '/') {
                ++$result;
            }
        }

        return $result;
    }
}


class Formula
{
    /**
     * @var Code
     */
    private $codes;

    public function __construct()
    {
        $this->setCodes();
    }

    public function setCodes()
    {
        $codes = explode("+", trim(fgets(STDIN)));

        foreach ($codes as $code) {
            $this->codes[] = new Code($code);
        }
    }


    public function calculate()
    {
        $result = 0;

        foreach ($this->codes as $code) {
            $result += $code->getValue();
        }

        echo $result . PHP_EOL;
    }
}

$formula = new Formula();
$formula->calculate();