<?php

namespace me\adamcameron\general\db\test\unit\repository;

use me\adamcameron\general\db\dao\PeopleDAO;
use me\adamcameron\general\db\model\Name;
use me\adamcameron\general\db\model\Person;
use me\adamcameron\general\db\repository\PeopleRepository;
use PHPUnit\Framework\TestCase;

class PeopleRepositoryTest extends TestCase
{
    /** @var PeopleDAO|\PHPUnit_Framework_MockObject_MockObject */
    private $dao;

    /** @var PeopleRepository */
    private $repo;

    public function setup()
    {
        $this->setDao();
        $this->repo = new PeopleRepository($this->dao);
    }

    public function testGetNameByFirstChar()
    {
        $data = [
            'Ava' => ['id' => 5, 'rank' => 3, 'gender' => 'F'],
            'Amelia' => ['id' => 9, 'rank' => 5, 'gender' => 'F'],
            'Adam' => ['id' => 11, 'rank' => 6, 'gender' => 'M'],
            'Alex' => ['id' => 25, 'rank' => 13, 'gender' => 'M']
        ];

        $this->dao
            ->method('getNameByFirstChar')
            ->willReturn($data);

        $testLetter = 'a';
        $people = $this->repo->getNameByFirstChar($testLetter);

        $this->assertCount(count($data), $people);

        reset($data);

        /** @var $person Person */
        foreach ($people as $i =>  $person) {
            $this->assertInstanceOf(Person::class, $person);

            $this->assertSame(key($data), $person->firstName);
            $row = current($data);
            $this->assertSame($row['id'], $person->id);
            $this->assertSame($row['rank'], $person->rank);
            $this->assertSame($row['gender'], $person->gender);
            next($data);
        }
    }

    private function setDao() : void
    {
        $this->dao = $this
            ->getMockBuilder(PeopleDAO::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
