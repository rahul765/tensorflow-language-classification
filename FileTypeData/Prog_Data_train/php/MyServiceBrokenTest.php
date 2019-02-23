<?php

namespace me\adamcameron\phpunit\test\dataprovider\service;

use me\adamcameron\phpunit\dataprovider\repository\AuditRepository;
use me\adamcameron\phpunit\dataprovider\repository\DatabaseRepository;
use me\adamcameron\phpunit\dataprovider\repository\SocialMediaRepository;
use me\adamcameron\phpunit\dataprovider\service\MyService;
use PHPUnit\Framework\TestCase;

class MyServiceBrokenTest extends TestCase
{

    private $dbRepository;
    private $smRepository;
    private $auditRepository;
    private $myService;

    public function setup()
    {
        $this->myService = new MyService($this->dbRepository, $this->smRepository);
    }

    /** @dataProvider provideCasesForMyMethodTests */
    public function testMyMethod($data, $expected)
    {
        $mockedMethod = $this->dbRepository
            ->expects($this->exactly($expected['dbRepository']))
            ->method('doDbStuff');

        if ($expected['dbRepository'] > 0) {
            $mockedMethod->with($data['db']);
        }

        $mockedMethod = $this->smRepository
            ->expects($this->exactly($expected['smRepository']))
            ->method('doSocialStuff');

        if ($expected['smRepository'] > 0) {
            $mockedMethod->with($data['social']);
        }

        $this->myService->myMethod($data);
    }

    public function provideCasesForMyMethodTests()
    {
        $this->dbRepository = $this
            ->getMockBuilder(DatabaseRepository::class)
            ->getMock();
        $this->smRepository = $this
            ->getMockBuilder(SocialMediaRepository::class)
            ->getMock();

        $this->auditRepository = $this
            ->getMockBuilder(AuditRepository::class)
            ->getMock();


        return [
            'no optionals' => [
                'data' => [],
                'expected' => ['dbRepository' => 0, 'smRepository' => 0]
            ]/*,
            'db optional present' => [
                'data' => [
                    'db' => 'TEST_DB_VALUE'
                ],
                'expected' => ['dbRepository' => 1, 'smRepository' => 0]
            ],
            'social media optional present' => [
                'data' => [
                    'social' => 'TEST_SOCIAL_VALUE'
                ],
                'expected' => ['dbRepository' => 0, 'smRepository' => 1]
            ],
            'all optionals present' => [
                'data' => [
                    'db' => 'TEST_DB_VALUE',
                    'social' => 'TEST_SOCIAL_VALUE'
                ],
                'expected' => ['dbRepository' => 1, 'smRepository' => 1]
            ]*/
        ];
    }

    private function setMockedDependencies()
    {
        $this->dbRepository = $this
            ->getMockBuilder(DatabaseRepository::class)
            ->getMock();
        $this->smRepository = $this
            ->getMockBuilder(SocialMediaRepository::class)
            ->getMock();
    }
}
