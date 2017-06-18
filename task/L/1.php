<?php

class Quadrangle
{
    /**
     * @var int
     */
    private $depth;

    /**
     * @var int
     */
    private $wide;

    /**
     * Quadrangle constructor.
     * @param int $depth
     * @param int $wide
     */
    public function __construct($depth, $wide)
    {
        $this->depth = $depth;
        $this->wide = $wide;
    }

    /**
     * @return int
     */
    public function getAres()
    {
        return $this->wide * $this->depth;
    }
}

list(
    $length,
    $distance
    ) = explode(" ", trim(fgets(STDIN)));

$quadrangle = new Quadrangle((int) $length, (int) $distance);
echo $quadrangle->getAres() . PHP_EOL;