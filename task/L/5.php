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
    private $dataStructure = [];

    /**
     * @param int $value
     *
     * @return void
     */
    public function add($value)
    {
        $this->dataStructure[] = $value;
    }

    /**
     * @return void
     */
    public function remove()
    {
        if (count($this->dataStructure) === 0) {
            $this->output(self::UNEXPECTED_VALUE);
        }

        /**
         * dataStructureから先頭要素を削除
         */
        $value = array_shift($this->dataStructure);

        $this->output($value);
    }

    /**
     * @return void
     */
    public function min()
    {
        if (count($this->dataStructure) === 0) {
            $this->output(self::UNEXPECTED_VALUE);
        }

        $this->output(min($this->dataStructure));
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
        case preg_match('/^(add)\s(\d)/', $operation, $m):
            /**
             * $m[1]: method name
             * $m[2]: value
             */
            call_user_func_array([$operator, $m[1]], [$m[2]]);
            break;

        case preg_match('/^(min|remove)/', $operation, $m):
            /**
             * $m[1]: method name
             */
            call_user_func([$operator, $m[1]]);
            break;
    }
}