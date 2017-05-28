<?php

/**
 * 二桁に対応できない。。
 * str_split($targetString);のとき。。
 */

class LevelString
{
    /**
     * @var int
     */
    private $string;

    /**
     * @var int
     */
    private $count;

    public function __construct(string $string, int $count)
    {
        $this->string = $string;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPrevLevelStringCount(): int
    {
        return ($this->count - 3) / 2;
    }

    /**
     * @return int
     */
    public function getMiddleStringIndex(): int
    {
        return ($this->getPrevLevelStringCount() + 2);
    }
}


class StringAttacher
{
    const LOOP_STRING = 'ABC';

    private $level;

    private $start;

    private $end;

    private $length;

    private $levelStrings = [];

    public function __construct()
    {
        list(
            $this->level,
            $this->start,
            $this->end,
            ) = explode(" ", trim(fgets(STDIN))); // trim(fgets(STDIN))

        $this->setStartAndEnd();

        $this->levelStrings[1] = new LevelString(self::LOOP_STRING, strlen(self::LOOP_STRING));
        $this->pushLevelString();
    }

    private function setStartAndEnd()
    {
        $this->length = $this->end - ($this->start - 1); // $this->startに依存するので先にセットする
        $this->start = (int) $this->start;
        $this->end = (int) $this->end;
    }

    public function output()
    {
        $string = $this->reverseString();

        if (strlen($string) === 1) {
            echo $string . PHP_EOL;
            return;
        }
        /**
         * phpの文字列切り出しはイケてない。。
         * 第三引数が長さ指定なので、計算を行っている。
         * 例えば、1文字目と4文字目間で切り取りができない
         */
        echo substr($string, ($this->start - 1), $this->length) . PHP_EOL;
    }

    public function pushLevelString()
    {
        if ($this->level === 1) {
            return;
        }

        for ($i = 2; $i <= $this->level; $i++) {
            $this->levelStrings[$i] = $this->levelString($i);
        }
    }

    /**
     * @param int $nowLevel
     * @return string
     */
    private function levelString(int $nowLevel)
    {
        $prevLevelString = $this->levelStrings[$nowLevel-1];

        $strCount = ($prevLevelString->getCount() * 2) + 3;
        $str = "A|" . ($nowLevel-1) . "|B|" . ($nowLevel-1) . "|C";

        return new LevelString($str, $strCount);
    }

    private function reverseString(): string
    {
        krsort($this->levelStrings);

        if (count($this->levelStrings) === 1) {
            return $this->levelStrings[1];
        }

        /** @var LevelString $nowLevelString */
        $nowLevelString = $this->levelStrings[$this->level];

        switch (true) {
            case ($this->start - 1) === 0 && $this->length === 1:
                return 'A';

            case ($this->start - 1) === ($nowLevelString->getMiddleStringIndex() -1) && $this->length === 1:
                return 'B';

            case ($this->start - 1) === ($nowLevelString->getCount() -1);
                return 'C';
        }

        $targetString = $nowLevelString->getString();
        $prevLevel = --$this->level;

        while (preg_match('/' . $prevLevel . '/', $targetString)) {

            $prevLevelString = $this->levelStrings[$prevLevel];

            $replaceStringIndex = 0;

            $tmp = preg_split('/\|/', $targetString);

            $replaceFlg = true;

            foreach ($tmp as $key => $str) {

                if ($str == $prevLevel) {
                    $replaceStringIndex += $nowLevelString->getPrevLevelStringCount();

                    if ($this->start <= $replaceStringIndex) {

                        /**
                         * 初回に数字を置換するとき
                         */
                        if ($replaceStringIndex <= $nowLevelString->getCount()) {
                            $tmp[$key] =  $prevLevelString->getString();
                            continue;
                        }

                        /**
                         * 開始と終点の文字位置が変更対象の文字列の並び内にある
                         */
                        if ($this->end >= $replaceStringIndex) {
                            $tmp[$key] =  $prevLevelString->getString();
                            continue;
                        }

                        if ($this->end <= $replaceStringIndex && $replaceFlg) {
                            $tmp[$key] =  $prevLevelString->getString();
                            $replaceFlg = false;
                            continue;
                        }
                    }

                    if ($this->start >= $replaceStringIndex && $prevLevelString) {
                        $tmp[$key] =  $prevLevelString->getString();
                    }

                } else {
                    ++$replaceStringIndex;
                }
            }

            $targetString = implode($tmp);

            $nowLevelString = $this->levelStrings[$prevLevel];
            --$prevLevel;
        }

        return $targetString;
    }
}


$stringAttacher = new StringAttacher();
$stringAttacher->output();