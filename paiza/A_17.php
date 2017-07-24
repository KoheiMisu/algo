<?php

class Simulation
{
    /**
     * @var Screen
     */
    private $screen;

    /**
     * @var FallenObjectInterface[]
     */
    private $fallenObjects;

    public function __construct(
        $screenHeight,
        $screenWidth,
        $fallenObjectCount
    )
    {
        $this->screen = new Screen($screenHeight, $screenWidth, new History($screenWidth));
        $this->setFallenObjects($fallenObjectCount);
    }

    private function setFallenObjects($fallenObjectCount)
    {
//        $testData = [
//            '1 8 1',
//            '4 1 5',
//            '1 6 2',
//            '2 2 0',
//        ];

        $testData = [
            '2, 2, 4',
            '2, 2, 3',
            '2, 2, 5',
            '3, 6, 2',
            '2, 2, 6',
            '2, 2, 1',
            '2, 2, 7',
            '2, 2, 0',
            '2, 1, 8'
        ];

        for ($i=0; $i < $fallenObjectCount; $i++) {
            list(
                $height,
                $width,
                $offset,
                ) = explode(" ", trim($testData[$i])); // explode(" ", trim(fgets(STDIN)));

            $this->fallenObjects[] = new Rectangle($height, $width, $offset);
        }
    }

    public function output()
    {
        // 落下物をシミュレートする
        $this->screen->operate($this->fallenObjects);

        // 落下物のシュミレーションを行う。落下物が自身が描画される座標を持つので、スクリーンの変換を行う
        $this->screen->transformCoordinates($this->fallenObjects);

        $result = $this->screen->getCoordinates();
        krsort($result);

        foreach ($result as $row => $line) {
            echo implode($line, '') . PHP_EOL;
        }
    }
}

interface FallenObjectInterface
{
    /**
     * @return array
     */
    public function getFiguredCoordinates();

    /**
     * @param array $origin
     *
     * @return void
     */
    public function setFiguredCoordinates($origin);
}

class Rectangle implements FallenObjectInterface
{
    /**
     * @var array
     */
    private $figuredCoordinates;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $offset;

    public function __construct(
        $height,
        $width,
        $offset
    )
    {
        $this->height = $height;
        $this->width = $width;
        $this->offset = $offset;
    }

    /**
     * @return array
     */
    public function getFiguredCoordinates()
    {
        return $this->figuredCoordinates;
    }

    /**
     * @param array $origin
     *
     * @return void
     */
    public function setFiguredCoordinates($origin)
    {
        // 起点である$originから描画される座標配列を作る
        $originX = key($origin);
        $originY = $origin[$originX];

        $limitX = $originX + $this->width - 1;
        $limitY = $originY + $this->height - 1;

        switch (true) {
            case $this->height == 1 && $this->width == 1:
                $this->figuredCoordinates[] = [$originX => $originY];
                break;

            case $this->height == 1:
                for ($x = $originX; $x <= $limitX; $x++) {
                    $this->figuredCoordinates[] = [$x => $originY];
                }
                break;

            case $this->width == 1:
                for ($y = $originY; $y <= $limitY; $y++) {
                    $this->figuredCoordinates[] = [$originX => $y];
                }
                break;

            default:
                for ($y = $originY; $y <= $limitY; $y++) {
                    for ($x = $originX; $x <= $limitX; $x++) {
                        $this->figuredCoordinates[] = [$x => $y];
                    }
                }
        }
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}

class Screen
{
    /**
     * @var array
     */
    private $coordinates;

    /**
     * @var History
     */
    private $history;

    public function __construct($screenHeight, $screenWidth, History $history)
    {
        for ($i=0; $i < $screenHeight; $i++) {
            $this->coordinates[] = array_fill(0, $screenWidth, '.');
        }

        $this->history = $history;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function transformCoordinates(array $fallenObjects)
    {
        /**
         * @var FallenObjectInterface $fallenObject
         */
        foreach ($fallenObjects as $key => $fallenObject) {
            foreach ($fallenObject->getFiguredCoordinates() as $coordinate) {
                $x = key($coordinate);
                $y = $coordinate[$x];

                $this->coordinates[$y][$x] = '#';
            }
        }
    }

    public function operate(array $fallenObjects)
    {
        /** @var FallenObjectInterface $fallenObject */
        foreach ($fallenObjects as $fallenObject) {
            $origin = $this->calcOrigin($fallenObject);
            $fallenObject->setFiguredCoordinates($origin);
        }
    }

    /**
     * @param FallenObjectInterface $fallenObject
     *
     * @return array
     */
    private function calcOrigin($fallenObject)
    {
        $y = $this->history->getHeight($fallenObject);

        $this->history->setHeight($fallenObject);

        return [$fallenObject->getOffset() => $y];
    }
}

class History
{
    /**
     * @var array
     */
    private $coordinates;

    public function __construct($screenWidth)
    {
        for ($x = 0; $x < $screenWidth; $x++) {
            $this->coordinates[] = 0;
        }
    }

    public function getHeight($fallenObject)
    {
        $y = $this->coordinates[$fallenObject->getOffset()];

        /**
         * widthの範囲内で一番値の大きい値を返す
         */
        for ($x = $fallenObject->getOffset(); $x < ($fallenObject->getOffset() + $fallenObject->getWidth()); $x++) {
            if ($this->coordinates[$x] > $y) {
                $y = $this->coordinates[$x];
            }
        }

//        var_dump($this->coordinates);
//
//        echo PHP_EOL;

//        var_dump($y);

        return $y;
    }

    public function setHeight($fallenObject)
    {
        // 上書きする範囲の配列
        $arr = array_slice($this->coordinates, $fallenObject->getOffset(), ($fallenObject->getOffset() + $fallenObject->getWidth()));

        // 範囲内の最大値を取得
        $baseValue = max($arr);

        for ($x = $fallenObject->getOffset(); $x < ($fallenObject->getOffset() + $fallenObject->getWidth()); $x++) {
            $this->coordinates[$x] = $baseValue + $fallenObject->getHeight();
        }
    }
}

//list(
//    $screenHeight,
//    $screenWidth,
//    $fallenObjectCount,
//    ) = [10, 10, 4]; // explode(" ", trim(fgets(STDIN)));
//
//$Simulation = new Simulation($screenHeight, $screenWidth, $fallenObjectCount);
//
//$Simulation->output();

$arr[1][2] = 'a';
$arr[1][3] = 'a';

var_dump($arr);
