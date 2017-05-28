<?php

/**
 * refs https://paiza.jp/challenges/183/show
 *
 *
 */


class StringAttacher
{
    const REPLACE_KEY = 'R';

    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $start;

    /**
     * @var int
     */
    private $end;

    /**
     * @var string
     */
    private $string = 'ABC';

    /**
     * StringAttacher constructor.
     */
    public function __construct()
    {
        list(
            $this->level,
            $this->start,
            $this->end,
            ) = explode(" ", '20 1 1');

        $this->attachStringByLevel();
    }

    /**
     * @return void
     */
    public function output()
    {
        /**
         * phpの文字列切り出しはイケてない。。
         * 第三引数が長さ指定なので、計算を行っている。
         * 例えば、1文字目と4文字目間で切り取りができない
         */
        echo substr($this->string, $this->start - 1, $this->end - ($this->start - 1)) . PHP_EOL;
    }

    /**
     * @return void
     */
    public function attachStringByLevel()
    {
        if ($this->level === 1) {
            return;
        }

        $levelStrings[1] = self::REPLACE_KEY;

        for ($i = 2; $i <= $this->level; $i++) {
            $levelStrings[$i] = $this->attach($levelStrings, $i);
            unset($levelStrings[$i-1]);
        }

        $this->string = $levelStrings[$this->level];
    }

    /**
     * @param array $levelString
     * @param int $nowLevel
     * @return string
     */
    private function attach(array $levelString, int $nowLevel): string
    {
        $str = "A" . $levelString[$nowLevel-1] . "B" . $levelString[$nowLevel-1] . "C";

        return $str;
    }
}


$stringAttacher = new StringAttacher();
$stringAttacher->output();
