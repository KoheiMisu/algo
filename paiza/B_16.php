<?php

/**
 * refs https://paiza.jp/challenges/61/show
 *
 * @Todo 全てのテスト通るように改修
 */

class Map
{
    /**
     * @var int
     */
    private $side;

    /**
     * @var int
     */
    private $length;


    /**
     * Map constructor.
     * @param int $side
     * @param int $length
     */
    public function __construct(
        int $side,
        int $length
    )
    {
        // @Todo ここの -1や計算ロジックでの +1 をうまく吸収したい
        $this->side = $side - 1;
        $this->length = $length - 1;
    }

    /**
     * @return int
     */
    public function getSide(): int
    {
        return $this->side;
    }

    /**
     * @param int $side
     */
    public function setSide(int $side)
    {
        $this->side = $side;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length)
    {
        $this->length = $length;
    }
}

class Position
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * Position constructor.
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }
}

class Log
{
    /**
     * @var string
     */
    private $direction;

    /**
     * @var int
     */
    private $distance;

    /**
     * Log constructor.
     * @param string $direction
     * @param int $distance
     */
    public function __construct(string $direction, int $distance)
    {
        $this->direction = $direction;
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     */
    public function setDistance(int $distance)
    {
        $this->distance = $distance;
    }
}

class Game
{
    /**
     * @var
     */
    private $map;

    /**
     * @var
     */
    private $logs;

    /**
     * @var
     */
    private $position;

    /**
     * Game constructor.
     * @param int $side
     * @param int $length
     * @param int $logCount
     */
    public function __construct(
        int $side,
        int $length,
        int $logCount
    )
    {
        $this->setPosition();
        $this->setMap($side, $length);
        $this->setLog($logCount);
    }

    /**
     *
     */
    private function setPosition()
    {
        list(
            $x,
            $y
            ) = explode(" ", trim(fgets(STDIN)));

        $this->position = new Position((int)$x, (int)$y);
    }

    /**
     * @param int $side
     * @param int $length
     */
    private function setMap(int $side, int $length)
    {
        $this->map = new Map($side, $length);
    }

    /**
     * @param int $logCount
     */
    private function setLog(int $logCount)
    {
        while ($logCount !== 0) {
            --$logCount;

            list(
                $direction,
                $distance
                ) = explode(" ", trim(fgets(STDIN)));

            $this->logs[] = new Log($direction, (int)$distance);
        }
    }

    /**
     *
     */
    public function parsePosition()
    {
        foreach ($this->logs as $log) {
            Calculator::calcPosition($log, $this->map, $this->position);
        }

        echo $this->position->getX() . ' ' . $this->position->getY();
    }
}

class Calculator
{
    /**
     * @param Log $log
     * @param Map $map
     * @param Position $position
     */
    public static function calcPosition(Log $log, Map $map, Position $position)
    {
        switch (true) {
            case $log->getDirection() === 'U':
            case $log->getDirection() === 'D':
                $getMethod = 'getY';
                $setMethod = 'setY';
                break;
            case $log->getDirection() === 'R':
            case $log->getDirection() === 'L':
                $getMethod = 'getX';
                $setMethod = 'setX';
                break;
        }

        $result = 0;

        switch (true) {
            case $log->getDirection() === 'U':
                $result = $position->$getMethod() + $log->getDistance();
                if ($result > $map->getLength()) {
                    $result = $map->getLength() - $result + 1;
                }
                break;
            case $log->getDirection() === 'L':
                $result = $position->$getMethod() - $log->getDistance();
                if ($result < 0) {
                    $result = $map->getSide() - $result + 1;
                }
                break;
            case $log->getDirection() === 'D':
                $result = $position->$getMethod() - $log->getDistance();
                if ($result < 0) {
                    $result = $map->getLength() - $result + 1;
                }
                break;
            case $log->getDirection() === 'R':
                $result = $position->$getMethod() + $log->getDistance();
                if ($result > $map->getSide()) {
                    $result = $map->getSide() - $result + 1;
                }
                break;
        }

        $position->$setMethod(abs($result));
    }
}

list(
    $side,
    $length,
    $logCount,
    ) = explode(" ", trim(fgets(STDIN)));

$Game = new Game((int)$side , (int)$length, (int)$logCount);

$Game->parsePosition();