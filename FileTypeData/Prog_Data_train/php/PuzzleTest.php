<?php

namespace me\adamcameron\general\puzzle\test;

use me\adamcameron\general\puzzle\Puzzle;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \me\adamcameron\puzzle\Puzzle */
class PuzzleTest extends TestCase
{

    private $puzzle;

    function setup()
    {
        $this->puzzle = new Puzzle();
    }

    /**
     * @covers ::getSubsetsWithTotal
     * @dataProvider provideCasesFromGuidelines
     */
    function testGetSubsetsWithTotalWithCasesFromGuidelines($array, $sum, $expected)
    {
        $actual = $this->puzzle->getSubsetsWithTotal($array, $sum);

        $this->assertEquals($expected, $actual);
    }

    function provideCasesFromGuidelines()
    {
        return [
            'baseline 1' => [
                'array' => [1,2,3,4,5],
                'sum' => 5,
                'expected' => [[1,4],[2,3],[5]]
            ],
            'baseline 2' => [
                'array' => [1,2,3,4,5,6,7,8,9,10],
                'sum' => 9,
                'expected' => [[1,2,6],[1,3,5],[2,3,4],[1,8],[2,7],[3,6],[4,5],[9]]
            ],
            "Isaiah's example" => [
                'array' => [1,1,2,3,3,4],
                'sum' => 6,
                'expected' => [[1,1,4],[1,2,3],[2,4],[3,3]]
            ]
        ];
    }

}
