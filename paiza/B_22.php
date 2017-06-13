<?php
/**
 * refs https://paiza.jp/challenges/82/show
 */

// 立候補者 Candidates

// 有権者 Voter

// 演説 Speech

// 選挙 Election




// 有権者は自分の支持している有権者の番号を持つ(キーは0から)

// 立候補者は、n人の立候補者をもつ

/**
 * 演説が行われると
 * ① 誰も支持していない有権者は演説した立候補者につく
 *
 * ② 立候補者は、同時に立候補している者がもつ有権者を一人ずつ獲得できる
 *
 */

class Candidate
{
    /**
     * @var array
     */
    private $followVoter;

    public function __construct()
    {
        $this->followVoter = [];
    }

    public function manageFollowVoter(string $type, int $voterIndex)
    {
        switch (true) {

            case $type === 'plus' :
                $this->followVoter[] = $voterIndex;
                break;

            case $type === 'minus' :
                $this->followVoter[] = $voterIndex;
                break;
        }
    }

    public function getFollowVoterCount(): int
    {
        return count($this->followVoter);
    }

    public function receiveVoter(Candidate $otherCandidate)
    {
        // array_shiftで配列を一度だけ破壊したいので、変数に格納して使う
        $voter = $otherCandidate->popVoter();
        if ($voter !== 0) {
            $this->manageFollowVoter('plus', $voter);
        }
    }

    /**
     * @return int
     */
    public function popVoter(): int
    {
        if (count($this->followVoter) > 0) {
            return array_shift($this->followVoter);
        }

        return 0;
    }
}

class Voter
{
    /**
     * @var int
     */
    private $followCandidate;

    public function __construct()
    {
        $this->followCandidate = 0;
    }

    /**
     * @param int $followCandidate
     */
    public function setFollowCandidate(int $followCandidate)
    {
        $this->followCandidate = $followCandidate;
    }

    public function getFollowCandidate(): int
    {
        return $this->followCandidate;
    }

    public function isFollow(): bool
    {
        return $this->followCandidate !== 0 ? true : false;
    }
}

class Election
{
    private $candidates;

    private $voters;

    private $speeches;

    public function __construct()
    {
        list(
            $candidateCount,
            $voterCount,
            $speechCount,
            ) = explode(" ", trim(fgets(STDIN)));

        $this->setCandidates((int) $candidateCount);
        $this->setVoters((int) $voterCount);
        $this->setSpeeches((int) $speechCount);
    }

    public function setCandidates(int $candidateCount)
    {
        for ($i = 1; $i <= $candidateCount; $i++) {
            $this->candidates[$i] = new Candidate();
        }
    }

    public function setVoters(int $voterCount)
    {
        for ($i = 1; $i <= $voterCount; $i++) {
            $this->voters[$i] = new Voter();
        }
    }

    public function setSpeeches(int $speechCount)
    {
        for ($i = 1; $i <= $speechCount; $i++) {
            $this->speeches[$i] = (int) trim(fgets(STDIN));
        }
    }

    public function disclosure()
    {
        foreach ($this->speeches as $key => $speech) {

            /** @var Candidate $candidate */
            $candidate = $this->candidates[$speech];

            // 無所属有権者の処理
            if (isset($this->voters[$key])) {
                /** @var Voter $voter */
                $voter = $this->voters[$key];
                $voter->setFollowCandidate($speech);

                $candidate->manageFollowVoter('plus', $key);
            }

            /**
             * どこかに所属している有権者の処理
             *
             * 演説者以外の候補者から演説者に有権者を渡す
             */
            $otherCandidates = array_filter(array_keys($this->candidates), function ($k) use ($key) {
                return ($k !== $key);
            });

            foreach ($otherCandidates as $candidateKey) {
                $otherCandidate = $this->candidates[$candidateKey];
                $candidate->receiveVoter($otherCandidate);
            }
        }

        // 結果
        $result = [0];
        foreach ($this->candidates as $key => $candidate) {
            $max = max($result);
            if ($max === $candidate->getFollowVoterCount()) {
                $result[$key] = $candidate->getFollowVoterCount();
                continue;
            }

            if ($max < $candidate->getFollowVoterCount()) {
                unset($result);
                $result[$key] = $candidate->getFollowVoterCount();
            }
        }

        foreach ($result as $candidateName => $voterCount) {
            if ($candidateName !== 0) {
                echo $candidateName . PHP_EOL;
            }
        }
    }
}

$election = new Election();

$election->disclosure();