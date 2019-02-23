<?php

namespace me\adamcameron\silex2\test\integration\repository;

use me\adamcameron\silex2\app\Application;
use me\adamcameron\silex2\exception\DatabaseException;
use me\adamcameron\silex2\model\Number;
use PHPUnit\Framework\TestCase;

class NumberRepositoryTest extends TestCase
{
    private $repository;

    public function setup()
    {
        $app = new Application([]);
        $this->repository = $app['repository.number'];
    }

    public function testWithValidNumber()
    {
        $id = 4;
        $lang = 'mi';
        $result = $this->repository->getNumberByIdAndLanguage($id, $lang);

        $expected = new Number($id, 'wha');
        $this->assertEquals($expected, $result);
    }

    public function testWithInvalidNumber()
    {
        $id = -1;
        $lang = 'en';
        $result = $this->repository->getNumberByIdAndLanguage($id, $lang);

        $this->assertNull($result);
    }

    public function testWithInvalidLanguage()
    {
        $this->expectException(DatabaseException::class);

        $id = 4;
        $lang = 'zz';
        $this->repository->getNumberByIdAndLanguage($id, $lang);
    }
}
