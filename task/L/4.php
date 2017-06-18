<?php

/**
 * Class Operator
 *
 * 当クラスのメソッドで受ける$key, $valueは
 * 2^30以下の自然数と仮定する
 */
class Operator
{
    const UNEXPECTED_VALUE = -1;

    /**
     * @var array
     */
    private $history = [];

    /**
     * @var array
     */
    private $dataStructure = [];

    /**
     * @param int $key
     * @param int $value
     *
     * @return void
     */
    public function add($key, $value)
    {
        $this->dataStructure[$key] = $value;
        $this->addHistory($key);
    }

    /**
     * @param int $key
     *
     * @return void
     */
    public function get($key)
    {
        try {
            $this->output($this->getValueByKey($key));
        } catch (Exception $e) {
            $this->output($e->getMessage());
            return;
        }

        $this->addHistory($key);
    }

    /**
     * @param int $key
     *
     * @return void
     */
    public function remove($key)
    {
        try {
            $value = $this->getValueByKey($key);
        } catch (Exception $e) {
            $this->output($e->getMessage());
            return;
        }

        /**
         * historyから該当のキーを削除する
         */
        unset($this->history[$key]);

        /**
         * dataStructureから該当キーを削除する
         */
        unset($this->dataStructure[$key]);

        $this->output($value);
    }

    /**
     * @return void
     */
    public function evict()
    {
        if (count($this->history) > 0) {
            $unsetKey = array_shift($this->history);
            unset($this->dataStructure[$unsetKey]);
        }
    }

    /**
     * @param int $key
     *
     * @return void
     */
    public function addHistory($key)
    {
        /**
         * 重複しているキーがある場合は削除する
         */
        $removeKey = array_search($key, $this->history);
        if ($key) {
            unset($this->history[$removeKey]);
        }

        $this->history[] = $key;
    }

    /**
     * @param int $key
     * @throws Exception $this->dataStructure にないキーにアクセスした場合
     *
     * @return int
     */
    private function getValueByKey($key)
    {
        if (isset($this->dataStructure[$key])) {
            return $this->dataStructure[$key];
        }

        throw new Exception(self::UNEXPECTED_VALUE);
    }

    /**
     * @param int $value
     *
     * @return void
     */
    private function output($value)
    {
        echo $value . PHP_EOL;
    }
}

$operator = new Operator();
$operations = [];

/**
 * 入力された値をセットする
 */
while (true) {
    $operation = trim(fgets(STDIN));
    $operations[] = $operation;

    if ($operation === 'exit') {
        break;
    }
}

/**
 * 入力値に対する処理を実行
 */
foreach ($operations as $operation) {
    switch (true) {
        case preg_match('/^(add)\s(\d)\s(\d)/', $operation, $m):
            /**
             * $m[1]: method name
             * $m[2]: key
             * $m[3]: value
             */
            call_user_func_array([$operator, $m[1]], [$m[2], $m[3]]);
            break;

        case preg_match('/^(get|remove)\s(\d)/', $operation, $m):
            /**
             * $m[1]: method name
             * $m[2]: key
             */
            call_user_func_array([$operator, $m[1]], [$m[2]]);
            break;

        case preg_match('/^(evict)/', $operation, $m):
            /**
             * $m[1]: method name
             */
            call_user_func([$operator, $m[1]]);
            break;
    }
}