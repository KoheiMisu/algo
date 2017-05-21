<?php
/**
 * Created by PhpStorm.
 * User: kouhei
 * Date: 2017/05/20
 * Time: 20:01
 */




/**
 * $Aの配列の各値は、配列の長さよりは小さい
 *
 *
 *
 *
 */

// you can write to stdout for debugging purposes, e.g.
// print "this is a debug message\n";

/**
 * @param array $inputValue
 *
 * @return int
 */
function solution(array $inputValue): int {
    // write your code in PHP7.0
    $validator = new Validator();

    $calculator = new Calculator($validator);

    $calculator->validate($inputValue);

    $result = $calculator->calculate($inputValue);

    return $result;
}

class Validator
{
    const MAX_SLICE_LENGTH = 100000;

    const MAX_SLICE_ELEMENT = 10000;

    const MIN_SLICE_ELEMENT = -10000;

    /**
     * @param array $arg
     * @return $this
     * @throws Exception
     */
    public function checkArgumentLength(array $arg)
    {
        $arrayLength = count($arg);

        if ($arrayLength > self::MAX_SLICE_LENGTH) {
            throw new Exception('slice length is invalid. now :' . $arrayLength . ', limit:' . self::MAX_SLICE_LENGTH);
        }
    }

    /**
     * @param array $arg
     * @throws Exception
     */
    public function checkEachElementValue(array $arg)
    {
        foreach ($arg as $index => $value) {
            if ($value < self::MIN_SLICE_ELEMENT || $value > self::MAX_SLICE_LENGTH) {
                $message = self::MIN_SLICE_ELEMENT . ' ~ ' . self::MAX_SLICE_ELEMENT;
                throw new Exception('argument element is invalid. value is within '. $message);
            }
        }
    }
}

class Calculator
{
    const COUNTER_LIMIT = 1000000000;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Calculator constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $arg)
    {
        $this->validator->checkArgumentLength($arg);
        $this->validator->checkEachElementValue($arg);
    }

    /**
     * @param array $arg
     * @return int
     */
    public function calculate(array $arg): int
    {
        $clonedArg = $arg;
        $counter = 0;

        foreach ($arg as $index => $value) {
            unset($clonedArg[$index]);

            if ($value === 0) {
                ++$counter;
            }

            $sum = $value;
            foreach ($clonedArg as $item) {
                $sum += $item;
                if ($sum === 0) {
                    ++$counter;
                }
            }

            if ($counter > self::COUNTER_LIMIT) {
                $counter = -1;
                break;
            }
        }

        return $counter;
    }
}