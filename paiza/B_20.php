<?php

/**
 * refs https://paiza.jp/challenges/74/ready
 *
 * @Todo 全然テストケース通らないので今度やり直したい
 *
 * 最初は必ずblank pageを開く
 *
 * go to blank page
 * go to [link]
 * use the back button
 */

class Action
{
    const BLANK = 'go to blank page';

    const BACK = 'use the back button';

    const LINK = 'go to ';
}

class Access
{
    /**
     * @var string
     */
    private $page;

    public function __construct(string $actionResult)
    {
        $this->page = $actionResult;
    }

    public function getPage(): string
    {
        return $this->page;
    }
}

class History
{
    /**
     * @var Access[]
     */
    private $accesses;

    /**
     * @var int
     */
    private $accessIndex;

    public function __construct()
    {
        $this->accessIndex = 0;
    }

    /**
     * @param string $action
     * @param int $index
     */
    public function setAccess(string $action, int $index)
    {
        $actionResult = $this->dispatchAction($action, $index);
        $this->accesses[] = new Access($actionResult);
    }

    /**
     * @param string $action
     * @param int $index
     * @return string
     */
    private function dispatchAction(string $action, int $index): string
    {
        if ($index === 0 || $action === Action::BLANK) {
            return preg_replace('/'. Action::LINK .'/', '', $action);
        }

        if ($action === Action::BACK) {
            return $this->getPrevAccess($index);
        }

        /**
         * 上記に当てはまらない場合は別ページへのリンク
         */
        preg_match('/^'. Action::LINK .'(.*)/', $action, $m);

        return $m[1];
    }

    /**
     * @return array
     */
    public function getAccesses(): array
    {
        return $this->accesses;
    }

    /**
     * @param int $index
     * @return string
     */
    private function getPrevAccess(int $index): string
    {
        if ($this->accessIndex === 0) {
            $this->accessIndex = $index - 2;
        } else {
            --$this->accessIndex;
        }

        $prevAccess = $this->accesses[$this->accessIndex];

        return $prevAccess->getPage();
    }
}

class Browser
{
    /**
     * @var int
     */
    private $accessCount;

    /**
     * @var History
     */
    private $history;

    /**
     * Browser constructor.
     */
    public function __construct()
    {
        $this->accessCount = (int)trim(fgets(STDIN));
        $this->history = new History();

        for ($i = 0; $i < $this->accessCount; $i++) {
            $action = trim(fgets(STDIN));
            $this->history->setAccess($action, $i);
        }
    }

    /**
     * @return void
     */
    public function show()
    {
        foreach ($this->history->getAccesses() as $access) {
            echo $access->getPage() . PHP_EOL;
        }
    }
}


$browser = new Browser();

$browser->show();