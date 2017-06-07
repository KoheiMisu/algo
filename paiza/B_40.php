<?php

/**
 * refs https://paiza.jp/challenges/179/show
 */

const ALPHABET = 'abcdefghijklmnopqrstuvwxyz';

class Decoder
{
    /**
     * @var array
     */
    private $replaceText;

    /**
     * @var EncodedWords
     */
    private $encodedWords;

    /**
     * Decoder constructor.
     * @param string $replaceCount
     * @param string $replaceText
     */
    public function __construct(string $replaceCount, string $replaceText)
    {
        for ($i = 1; $i < $replaceCount; $i++) {
            /**
             * @Todo 置換のアルゴリズムが不明なのでわかったら実装する
             */
        }
        $this->replaceText = str_split($replaceText);
    }

    /**
     * @param EncodedWords $encodedWords
     */
    public function setEncodedWords(EncodedWords $encodedWords)
    {
        $this->encodedWords = $encodedWords;
    }

    /**
     * @return void
     */
    public function decode()
    {
        $output = '';
        $master = str_split(ALPHABET);

        foreach ($this->encodedWords->getWords() as $word) {
            foreach ($word as $string) {
                $key = array_search($string, $this->replaceText);
                $output .= $master[$key];
            }

            $output .= ' ';
        }

        // 末尾には半角スペースが入るので取り除く
        echo trim($output) . PHP_EOL;
    }
}

class EncodedWords
{
    /**
     * @var array
     */
    private $words;

    /**
     * EncodedWords constructor.
     * @param array $encodedText
     */
    public function __construct(array $encodedText)
    {
        foreach ($encodedText as $word) {
            $this->words[] = str_split($word);
        }
    }

    /**
     * @return array
     */
    public function getWords(): array
    {
        return $this->words;
    }
}

//list(
//    $replaceCount,
//    $replaceText,
//    ) = explode(' ', trim(fgets(STDIN)));

list(
    $replaceCount,
    $replaceText,
    ) = explode(' ', '100 poiuytrewqlkjhgfdsamnbvcxz');

//$encodedText = explode(' ', trim(fgets(STDIN)));

$encodedText = explode(' ', 'snn');

$encodedWords = new EncodedWords($encodedText);
$decoder = new Decoder($replaceCount, $replaceText);

$decoder->setEncodedWords($encodedWords);
$decoder->decode();




