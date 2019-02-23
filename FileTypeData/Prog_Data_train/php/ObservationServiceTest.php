<?php

namespace me\adamcameron\phpunit\test\mockobject\service;

use me\adamcameron\phpunit\mockobject\repository\ObservedRepository;
use me\adamcameron\phpunit\mockobject\service\ObservationService;
use PHPUnit\Framework\TestCase;

class ObservationServiceTest extends TestCase
{

    private $repo;
    private $service;

    public function setup()
    {
        $this->setMockedRepository();
        $this->service = new ObservationService($this->repo);
    }

    public function testActual()
    {
        $this->repo
            ->method('observeMe')
            ->will($this->returnCallback(function ($arg) {
                var_dump($this->repo);
            }));

        $result = $this->service->observeViaMe('TEST_ACTUAL');

        $this->assertSame(
            'ObservedRepository->observeMe executed with [TEST_ACTUAL]',
            $result
        );
    }

    private function setMockedRepository()
    {
        $this->repo = $this
            ->getMockBuilder(ObservedRepository::class)
            ->getMock();
    }

}
