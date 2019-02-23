<?php

namespace me\adamcameron\phpunit\test\mockobject\helper;

use PHPUnit\Framework\TestCase;

class MockHelperTest extends TestCase {

    private $mockHelper;
    private $mockResult = "MOCKED_MOCK";

    public function setup(){
        $this->mockHelper = new MockHelper();
    }

    public function testNullConstructorArgs() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForNullConstructorArgsTest());

        $mockedObject = $mockHelper->mockObject(TestClass::class);

        $this->assertEquals($this->mockResult, $mockedObject);
    }

    public function testEmptyConstructorArgs() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForEmptyConstructorArgsTest());

        $mockedObject = $mockHelper->mockObject(TestClass::class, null, []);

        $this->assertEquals($this->mockResult, $mockedObject);
    }

    public function testPopulatedConstructorArgs() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForPopulatedConstructorArgsTest());

        $mockedObject = $mockHelper->mockObject(TestClass::class, null, ['arg1','arg2']);

        $this->assertEquals($this->mockResult, $mockedObject);
    }

    public function testNullMethods() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForNullMethodsTest());

        $mockedObject = $mockHelper->mockObject(TestClass::class);

        $this->assertEquals($this->mockResult, $mockedObject);
    }

    public function testEmptyMethods() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForEmptyMethodsTest());

        $mockedObject = $mockHelper->mockObject(TestClass::class, []);

        $this->assertEquals($this->mockResult, $mockedObject);
    }

    public function testPopulatedMethods() {
        $mockHelper = $this->getMockHelper($this->getMockBuilderForPopulatedMethodsTest());

        $mockedObject = $mockHelper->mockObject(
            TestClass::class,
            ['testMethod'=>[]]
        );

        $this->assertInstanceOf(TestClass::class, $mockedObject);
    }

    private function getMockHelper($mockBuilder) {
        $partiallyMockedMockHelper = $this->getMockBuilder(MockHelper::class)
            ->setMethods(["getMockBuilder"])
            ->getMock();
        $partiallyMockedMockHelper->method("getMockBuilder")
            ->willReturn($mockBuilder);

        return $partiallyMockedMockHelper;
    }

    private function getMockBuilderForNullConstructorArgsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["disableOriginalConstructor", "setConstructorArgs", "getMock"])
            ->getMock();

        $mockedMockBuilder->expects($this->once())
            ->method("disableOriginalConstructor");
        $mockedMockBuilder->expects($this->never())
            ->method("setConstructorArgs");
        $mockedMockBuilder->method("getMock")
            ->willReturn($this->mockResult);

        return $mockedMockBuilder;
    }

    private function getMockBuilderForEmptyConstructorArgsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["disableOriginalConstructor", "setConstructorArgs", "getMock"])
            ->getMock();

        $mockedMockBuilder->expects($this->never())
            ->method("disableOriginalConstructor");
        $mockedMockBuilder->expects($this->once())
            ->method("setConstructorArgs")
            ->with([]);
        $mockedMockBuilder->method("getMock")
            ->willReturn($this->mockResult);

        return $mockedMockBuilder;
    }

    private function getMockBuilderForPopulatedConstructorArgsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["disableOriginalConstructor", "setConstructorArgs", "getMock"])
            ->getMock();

        $mockedMockBuilder->expects($this->never())
            ->method("disableOriginalConstructor");
        $mockedMockBuilder->expects($this->once())
            ->method("setConstructorArgs")
            ->with(['arg1','arg2']);
        $mockedMockBuilder->method("getMock")
            ->willReturn($this->mockResult);

        return $mockedMockBuilder;
    }

    private function getMockBuilderForNullMethodsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["setMethods", "getMock"])
            ->getMock();

        $mockedMockBuilder->expects($this->never())
            ->method("setMethods");
        $mockedMockBuilder->method("getMock")
            ->willReturn($this->mockResult);

        return $mockedMockBuilder;
    }

    private function getMockBuilderForEmptyMethodsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["setMethods", "getMock"])
            ->getMock();

        $mockedMockBuilder->expects($this->once())
            ->method("setMethods")
            ->with([]);
        $mockedMockBuilder->method("getMock")
            ->willReturn($this->mockResult);

        return $mockedMockBuilder;
    }

    private function getMockBuilderForPopulatedMethodsTest(){
        $mockedMockBuilder = $this->getMockBuilder(\PHPUnit_Framework_MockObject_MockBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(["setMethods", "getMock", "method"])
            ->getMock();

        $mockedMockBuilder->expects($this->once())
            ->method("setMethods")
            ->with(["testMethod"]);
        $mockedMockBuilder->expects($this->once())
            ->method("method")
            ->with("testMethod");
        $mockedMockBuilder->expects($this->any())
            ->method("getMock")
            ->will($this->returnSelf());

        return $mockedMockBuilder;
    }

    private function getMockedGetMock(){

    }

}

class TestClass {

    function firstMethod(){

    }
}