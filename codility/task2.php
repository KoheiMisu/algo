<?php

class Validator
{
    /**
     * @param array $arg
     * @param int $minSliceLength
     * @param int $maxSliceLength
     * @throws Exception
     */
    public function checkArgumentLength(array $arg, int $minSliceLength, int $maxSliceLength)
    {
        $arrayLength = count($arg);

        if ($arrayLength < $minSliceLength || $arrayLength > $maxSliceLength) {
            $limit = $minSliceLength . ' ~ ' . $maxSliceLength;
            throw new Exception('slice length is invalid. now :' . $arrayLength . ', limit:' . $limit);
        }
    }

    /**
     * @param array $arg
     * @param int $minValue
     * @param int $maxValue
     * @throws Exception
     */
    public function checkEachElementValue(array $arg, int $minValue, int $maxValue)
    {
        foreach ($arg as $index => $value) {
            if ($value <= $minValue || $value >= $maxValue) {
                $message = $minValue . ' ~ ' . $maxValue;
                throw new Exception('argument element is invalid. value is within '. $message);
            }
        }
    }
}

class Chain implements CutMaterial
{
    const CUT_LOGIC = 'ChainLogic';

    /**
     * @var array
     */
    private $argument;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var int
     */
    private $count;

    /**
     * Chain constructor.
     * @param array $arg
     * @param Validator $validator
     */
    public function __construct(array $arg, Validator $validator)
    {
        $this->argument = $arg;
        $this->count = count($arg);
        $this->validator = $validator;
        $this->isRightObject();
    }

    /**
     * @return array
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * @return string
     */
    public function getCutLogic(): string
    {
        return self::CUT_LOGIC;
    }

    /**
     * @return int
     */
    public function getElementsCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $nowIndex
     * @return bool
     */
    public function isCuttableIndex(int $nowIndex): bool
    {
        /**
         * 先頭と末尾手前と末尾の配列は切断対象にならない
         */
        if ($nowIndex === 0 || $nowIndex >= ($this->getElementsCount() - 1)) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function isRightObject()
    {
        $this->validator->checkArgumentLength($this->argument, 5, 100000);
        $this->validator->checkEachElementValue($this->argument, 1, 1000000000);
    }

    /**
     * @param $key
     * @return int
     */
    public function getElement($key): int
    {
        return $this->argument[$key];
    }

    /**
     * @param int $index
     * @return array
     */
    public function getIndexedKeys(int $index): array
    {
        $func = function($value) use ($index) {
            if ($value !== 0 && ($value - $index) >= 2) {
                if ($value !== ($this->getElementsCount() - 1)) {
                    return $value;
                }
            }
        };

        return array_filter(array_keys($this->argument), $func);
    }

    /**
     * @param int $index
     * @param int $indexedKey
     * @return int
     */
    public function calculateCost(int $index, int $indexedKey): int
    {
        return $this->argument[$index] + $this->argument[$indexedKey];
    }
}

interface CutMaterial
{
    /**
     * @return mixed
     */
    public function getArgument();

    /**
     * @return string
     */
    public function getCutLogic(): string;

    /**
     * @param int $nowIndex
     * @return bool
     */
    public function isCuttableIndex(int $nowIndex): bool;

    /**
     * @param $key
     * @return int
     */
    public function getElement($key): int;

    /**
     * @param int $index
     * @return array
     */
    public function getIndexedKeys(int $index): array;
}

class Cutter
{
    /**
     * @var CutMaterial
     */
    private $cutMaterial;

    /**
     * @var CutLogic
     */
    private $cutLogic;

    /**
     * Cutter constructor.
     * @param CutMaterial $cutMaterial
     */
    public function __construct(CutMaterial $cutMaterial)
    {
        $this->cutMaterial = $cutMaterial;
        $this->setLogic();
    }

    /**
     * @return void
     */
    public function setLogic()
    {
        $logic = $this->cutMaterial->getCutLogic();
        $this->cutLogic = new $logic;
    }

    /**
     * @return int
     */
    public function cut(): int
    {
        return $this->cutLogic->cut($this->cutMaterial);
    }
}

interface CutLogic
{
    /**
     * @param CutMaterial $cutMaterial
     * @return mixed
     */
    public function cut(CutMaterial $cutMaterial);
}

class ChainLogic implements CutLogic
{
    /**
     * @var Cost
     */
    private $cost;

    /**
     * ChainLogic constructor.
     */
    public function __construct()
    {
        $this->cost = new Cost();
    }

    /**
     * @param CutMaterial $cutMaterial
     * @return int
     */
    public function cut(CutMaterial $cutMaterial): int
    {
        foreach ($cutMaterial->getArgument() as $index => $cost) {
            if (!$cutMaterial->isCuttableIndex($index)) {
                continue;
            }

            foreach ($cutMaterial->getIndexedKeys($index) as $indexedKey) {
                $cost = $cutMaterial->calculateCost($index, $indexedKey);
                $this->cost->setCost($cost);
            }
        }

        return $this->cost->getMinCost();
    }
}

class Cost
{
    /**
     * @var array
     */
    private $results=[];

    /**
     * @return int|bool
     */
    public function getMinCost()
    {
        return min($this->results);
    }

    /**
     * @param int $cost
     */
    public function setCost(int $cost)
    {
        $this->results[] = $cost;
    }
}

/**
 * @param array $inputValue
 *
 * @return int
 */
function solution(array $inputValue): int {
    // write your code in PHP7.0

    $validator = new Validator();

    $chain = new Chain($inputValue, $validator);

    $cutter = new Cutter($chain);

    $result = $cutter->cut();

    return $result;
}

$inputValue = [5, 2, 4, 6, 3, 7];

var_dump(solution($inputValue));