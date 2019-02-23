<?php

namespace returnValueMap;

class ActualTest extends \PHPUnit_Framework_TestCase
{
    private $mockedPackageResult = 'mocked_package_result';
    private $mockedPrepareResultPrefix = 'mocked_prepare_result';
    private $mockedProcessResultPrefix = 'mocked_process_result';
/*
    public function testActualiseWithMocksUsingReturnValueMap()
    {
        $actual = $this->getTestActualWithMocksUsingReturnValueMap();
        $result = $actual->actualise(null);

        $this->assertEquals($this->mockedPackageResult, $result);
    }
*/
    public function testActualiseWithMocksUsingReturnCallback()
    {
        $actual = $this->getTestActualWithMocksUsingReturnCallback();
        $result = $actual->actualise(null);

        $this->assertEquals($this->mockedPackageResult, $result);
    }

    private function getTestActualWithMocksUsingReturnValueMap()
    {
        $testObject = (object) ['property' => 'test property value'];

        $mockedPreparer = $this->mockPreparerUsingReturnValueMap();
        $mockedProcessor = $this->mockProcessorUsingReturnValueMap($testObject);
        $mockedPackager = $this->mockPackager();
        return new Actual($mockedPreparer, $mockedProcessor, $mockedPackager);
    }

    private function getTestActualWithMocksUsingReturnCallback()
    {
        $testObject = (object) ['property' => 'test property value'];

        $mockedPreparer = $this->mockPreparerUsingWithCallback();
        $mockedProcessor = $this->mockProcessorUsingWithCallback($testObject);
        $mockedPackager = $this->mockPackager();
        return new Actual($mockedPreparer, $mockedProcessor, $mockedPackager);
    }

    private function mockPreparerUsingReturnValueMap()
    {
        $mockedPrepareResultPrefix = $this->mockedPrepareResultPrefix;
        $valueMap = [
            ['actual_preparation_flag_1', "{$mockedPrepareResultPrefix}_1"],
            ['actual_preparation_flag_2', "{$mockedPrepareResultPrefix}_2"]
        ];

        $mockedPreparer = $this->getMockBuilder('Preparer')
            ->setMethods(['prepare'])
            ->getMock();

        $mockedPreparer
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->returnValueMap($valueMap));

        return $mockedPreparer;
    }

    private function mockPreparerUsingWithCallback()
    {
        $mockedPreparer = $this->getMockBuilder('Preparer')
            ->setMethods(['prepare'])
            ->getMock();

        $mockedPreparer
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->returnCallback(function($value){
                return "(MOCKED PREPARE: Value: $value)";
            }));

        return $mockedPreparer;
    }

    private function mockProcessorUsingReturnValueMap($testObject)
    {
        $mockedPrepareResultPrefix = $this->mockedPrepareResultPrefix;
        $mockedProcessResultPrefix = $this->mockedProcessResultPrefix;
        $valueMap = [
            ["{$mockedPrepareResultPrefix}_1", $testObject, "{$mockedProcessResultPrefix}_1"],
            ["{$mockedPrepareResultPrefix}_2", $testObject, "{$mockedProcessResultPrefix}_2"]
        ];

        $mockedProcessor = $this->getMockBuilder('Processor')
            ->setMethods(['process'])
            ->getMock();

        $mockedProcessor
            ->expects($this->exactly(2))
            ->method('process')
            ->will($this->returnValueMap($valueMap));

        return $mockedProcessor;
    }

    private function mockProcessorUsingWithCallback($testObject)
    {
        $mockedProcessor = $this->getMockBuilder('Processor')
            ->setMethods(['process'])
            ->getMock();

        $mockedProcessor
            ->expects($this->at(0))
            ->method('process')
            ->with('(MOCKED PREPARE: Value: actual_preparation_flag_1)',null)
            ->will($this->returnCallback([$this, 'mockedProcessCallback']));

        $mockedProcessor
            ->expects($this->at(1))
            ->method('process')
            ->with('(MOCKED PREPARE: Value: actual_preparation_flag_2)',null)
            ->will($this->returnCallback([$this, 'mockedProcessCallback']));

        return $mockedProcessor;
    }

    public function mockedProcessCallback($value, $object){
        return "(MOCKED PROCESS: Value: $value)";
    }

    private function mockPackager()
    {
        $mockedPackager = $this->getMockBuilder('Packager')
            ->setMethods(['package'])
            ->getMock();

        $mockedPackager
            ->expects($this->once())
            ->method('package')
            ->with(
                'actual_package_flag',
                [
                    'element1' => "(MOCKED PROCESS: Value: (MOCKED PREPARE: Value: actual_preparation_flag_1))",
                    'element2' => "(MOCKED PROCESS: Value: (MOCKED PREPARE: Value: actual_preparation_flag_2))"
                ]
            )
            ->willReturn($this->mockedPackageResult);

        return $mockedPackager;
    }
}
