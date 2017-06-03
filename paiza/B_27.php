<?php

/**
 * refs https://paiza.jp/challenges/106/show
 *
 * カードが残り二枚になった時点でゲーム終了、加算
 */

class Card
{
    /**
     * @var int
     */
    private $number;

    /**
     * Card constructor.
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}

/**
 * Cardの集合を扱う
 *
 * Class Table
 */
class Table
{
    private $cards;

    /**
     * Table constructor.
     *
     * @param int $length 縦
     * @param int $side 横
     */
    public function __construct(int $length, int $side)
    {
        for ($i = 1; $i <= $length; $i++) {
            $sideValues = explode(' ', trim(fgets(STDIN))); // trim(fgets(STDIN))

            for ($j = 1; $j <= $side; $j++) {
                $this->cards[$i][$j] = new Card((int)$sideValues[$j-1]);
            }
        }
    }

    /**
     * @return bool
     */
    public function isCardSame(): bool
    {
        list(
            $firstLength,
            $firstSide,
            $secondLength,
            $secondSide,
            ) = explode(' ', trim(fgets(STDIN)));

        if ($this->getCardNumber($firstLength, $firstSide) === $this->getCardNumber($secondLength, $secondSide)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $length
     * @param string $side
     * @return int
     */
    private function getCardNumber(string $length, string $side): int
    {
        $card = $this->cards[$length][$side];
        return $card->getNumber();
    }
}

/**
 * Class Player
 */
class Player
{
    /**
     * @var int
     */
    private $cardCount = 0;

    /**
     * @param int $count
     */
    public function addCardCount(int $count)
    {
        $this->cardCount = $this->cardCount + $count;
    }

    /**
     * @return int
     */
    public function getCardCount(): int
    {
        return $this->cardCount;
    }
}

class Game
{
    /**
     * @var Table
     */
    private $table;

    /**
     * @var Player[]
     */
    private $players;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->initializeGame();
    }

    /**
     * @return void
     */
    private function initializeGame()
    {
        list(
            $length,
            $side,
            $playerCount,
            ) = explode(' ', trim(fgets(STDIN))); // trim(fgets(STDIN))

        $this->table = new Table((int)$length, (int)$side);

        while ($playerCount !== 0) {
            --$playerCount;

            $this->players[] = new Player();
        }
    }

    /**
     * @retunr void
     */
    public function boot()
    {
        $playerNumber = 0;
        $historyLine = trim(fgets(STDIN));

        /**
         * ゲーム開始
         */
        for ($i = 0; $i < $historyLine; $i++) {
            if($this->table->isCardSame()) {
                $player = $this->players[$playerNumber];
                $player->addCardCount(2);
            } else {
                $playerNumber = $this->refreshPlayerNumber($playerNumber);
            }
        }

        /**
         * 結果
         */
        foreach ($this->players as $player) {
            echo $player->getCardCount() . PHP_EOL;
        }
    }

    /**
     * @param int $playerNumber
     * @return int
     */
    private function refreshPlayerNumber(int $playerNumber): int
    {
        ++$playerNumber;

        if (isset($this->players[$playerNumber])) {
            return $playerNumber;
        }

        return 0;
    }
}

$game = new Game();

$game->boot();