<?php

/**
 * refs https://paiza.jp/challenges/185/show
 *
 * @Todo fix for all test case
 */

class Result
{
    private $gold;

    private $silver;

    private $copper;

    private $result;

    public function __construct()
    {
        $this->setCountries();
    }

    public function setCountries()
    {
        $countryCount = (int) trim(fgets(STDIN));

        while ($countryCount !== 0) {
            --$countryCount;

            $result = trim(fgets(STDIN));
            $this->result[] = $result;

            list(
                $gold,
                $silver,
                $copper
                ) = explode(' ', $result);

            $this->gold[] = $gold;
            $this->silver[] = $silver;
            $this->copper[] = $copper;
        }

        arsort($this->gold);
        arsort($this->silver);
        arsort($this->copper);
    }

    public function sort()
    {
        $sortIndex = [];
        $goldFrequency = array_count_values($this->gold);

        foreach ($goldFrequency as $count => $frequency) {
            $keys = array_keys($this->gold, $count);

            if (count($keys) === 1) {
                $sortIndex[] = array_values($keys)[0];
                continue;
            }

            // 銀の数で比べる
            foreach ($keys as $sameValueKey) {
                $silversInSameGold[$sameValueKey] = $this->silver[$sameValueKey];
            }

            arsort($silversInSameGold);
            $silverFrequency = array_count_values($silversInSameGold);

            foreach ($silverFrequency as $silverCount => $silverValueCount) {
                if ($silverValueCount === 1) {
                    $sortIndex[] = array_search($silverCount, $this->silver);
                    continue;
                }

                // 銅の数で比べる
                $sameSilverKeys = array_keys($this->silver, $silverCount);
                foreach ($sameSilverKeys as $sameSilverValueKey) {
                    $coppersInSameSilver[$sameSilverValueKey] = $this->copper[$sameSilverValueKey];
                }

                arsort($coppersInSameSilver);
                foreach ($coppersInSameSilver as $cooperIndex => $copperCount) {
                    $sortIndex[] = $cooperIndex;
                    continue;
                }
            }
        }

        $sortIndex = array_unique($sortIndex);

        foreach ($sortIndex as $index) {
            echo $this->result[$index] . PHP_EOL;
        }
    }
}

$result = new Result();
$result->sort();
