<?php

namespace me\adamcameron\phpunit\test\dataprovider\service;

use PHPUnit\Framework\TestCase;

class MyServiceLoggedTest extends TestCase
{

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        error_log("IN CONSTRUCTOR  (" .spl_object_hash($this) . ")". PHP_EOL, 3, 'php://stderr');
        parent::__construct($name, $data, $dataName);
    }

    public function setup()
    {
        error_log("IN TEST  (" .spl_object_hash($this) . ")". PHP_EOL, 3, 'php://stderr');
    }

    /** @dataProvider provideCasesForMyMethodTests */
    public function testMyMethod($variant)
    {
        error_log("IN TEST  (" . spl_object_hash($this) . ")". PHP_EOL, 3, 'php://stderr');
        $this->assertTrue(true);
    }

    public function provideCasesForMyMethodTests()
    {
        error_log("IN DP  (" .spl_object_hash($this) . ")". PHP_EOL, 3, 'php://stderr');
        return [
            'tahi' =>['variant' => 1],
            'rua' =>['variant' => 2],
            'toru' =>['variant' => 3],
            'wha' =>['variant' => 4]
        ];
    }
}
