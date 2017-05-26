<?php

/**
 * refs https://paiza.jp/challenges/27/page/problem
 */

class Schedule
{
    /**
     * @var array
     */
    private $event = [];

    /**
     * Schedule constructor.
     */
    public function __construct()
    {
        $this->setEvent();
    }

    /**
     * Member:0 or Event:0
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        if (count($this->event) === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    private function setEvent()
    {
        $inputLines = trim(fgets(STDIN));
        list($memberCount, $eventCount) = explode(" ", $inputLines);

        if ($memberCount === 0 || $eventCount ===0) {
            return;
        }

        while ($inputLines = trim(fgets(STDIN))) {
            $profits = explode(" ", $inputLines);
            $this->event[] = new Event($profits);
        }
    }

    /**
     * @return array
     */
    public function getEvent(): array
    {
        return $this->event;
    }
}

class Event
{
    /**
     * @var int|float
     */
    private $profit;

    /**
     * Event constructor.
     * @param array $profits
     */
    public function __construct(array $profits)
    {
        $this->setProfit($profits);
    }

    /**
     * @param array $profits
     */
    private function setProfit(array $profits)
    {
        $this->profit = array_sum($profits);
    }

    /**
     * @return int|float
     */
    public function getProfit()
    {
        return $this->profit;
    }
}

interface CalculateResultInterface
{
    /**
     * @param int $value
     * @return void
     */
    public function setResult(int $value);

    /**
     * @return int
     */
    public function getResult(): int;
}

class Profit implements CalculateResultInterface
{
    /**
     * @var int
     */
    private $value = 0;

    /**
     * @param int $value
     */
    public function setResult(int $value)
    {
        $this->value = $value;
    }

    public function getResult(): int
    {
        if ($this->value < 0) {
            return 0;
        }

        return $this->value;
    }
}


class Calculator
{
    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @var CalculateResultInterface
     */
    private $calculateResult;

    /**
     * Calculator constructor.
     * @param Schedule $schedule
     * @param CalculateResultInterface $calculateResult
     */
    public function __construct(Schedule $schedule, CalculateResultInterface $calculateResult)
    {
        $this->schedule = $schedule;
        $this->calculateResult = $calculateResult;
    }

    /**
     * @return CalculateResultInterface
     */
    public function calculateProfit()
    {
        if ($this->schedule->isEmpty()) {
            $this->calculateResult->setResult(0);
            return $this->calculateResult;
        }

        $profit = 0;

        foreach ($this->schedule->getEvent() as $event) {
            if ($event->getProfit() > 0) {
                $profit += $event->getProfit();
            }
        }

        $this->calculateResult->setResult($profit);

        return $this->calculateResult;
    }
}

$profit = new Profit();

$schedule = new Schedule();

$calculator = new Calculator($schedule, $profit);

$result = $calculator->calculateProfit();

echo $result->getResult();


