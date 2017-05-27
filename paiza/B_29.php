<?php

/**
 * refs https://paiza.jp/challenges/113/show
 *
 * 目標(x, y)から地価の近い順にk個の点が与えられる
 * k点の地価の平均が予測される地価の値
 *
 * N個の点と地価が与えられるが、近いk個の数から値の平均をとる
 *
 * 距離は√(x1 - x)2 + (y1 - y)2
 */

class Land
{
    private $x = 0;

    private $y = 0;

    private $price = 0;

    public function __construct(string $arg)
    {
        $this->setArg($arg);
    }

    public function setArg(string $arg)
    {
        $arg = explode(" ", $arg);

        if (count($arg) === 2) {
            list($this->x, $this->y) = $arg;
            return;
        }

        list(
            $this->x,
            $this->y,
            $this->price,
            ) = $arg;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}

class Distance
{
    private $index;

    private $value;
}

class Calculator
{
    /**
     * @var array
     */
    private $lands=[];

    /**
     * @var Land
     */
    private $targetLand;

    /**
     * @var int 分母:k
     */
    private $averageDenominator;

    public function __construct()
    {
        $this
            ->setTargetLand()
            ->setAverageDenominator()
            ->setLands();
    }

    private function setTargetLand()
    {
        $this->targetLand = new Land(trim(fgets(STDIN)));
        return $this;
    }

    private function setAverageDenominator()
    {
        $this->averageDenominator = (int) trim(fgets(STDIN));
        return $this;
    }

    private function setLands()
    {
        // 問題のNは一旦使わないのでスキップさせる
        $inputLines = trim(fgets(STDIN));
        while ($inputLines = trim(fgets(STDIN))) {
            $this->lands[] = new Land($inputLines);
        }
    }

    public function calculatePrice()
    {
        $distances = [];
        $priceSum = 0;

        // 比較するlandの数と分母が同じの場合、全ての計算を行う
        if (count($this->lands) === $this->averageDenominator) {
            foreach ($this->lands as $land) {
                $priceSum += $land->getPrice();
            }

            echo round( $priceSum / $this->averageDenominator );
            return;
        }

        /** @var Land $land */
        foreach ($this->lands as $land) {
            // targetLandとの距離を記録
            $xDiff = $this->targetLand->getX() - $land->getX();
            $yDiff = $this->targetLand->getY() - $land->getY();
            $distances[] = sqrt( pow($xDiff, 2) + pow($yDiff, 2) );
        }

        // 距離の小さい順に並び替える
        asort($distances);

        $slicedDistances = array_slice($distances, 0, $this->averageDenominator, true);

        foreach ($slicedDistances as $key => $value) {
            $land = $this->lands[$key];
            $priceSum += $land->getPrice();
        }

        echo round( $priceSum / $this->averageDenominator );
    }
}

$calculator = new Calculator();
$calculator->calculatePrice();




