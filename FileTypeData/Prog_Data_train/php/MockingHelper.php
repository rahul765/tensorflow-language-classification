<?php

namespace me\adamcameron\general\reflection\privateMethods\usingPhpUnit;

class MockingHelper
{

    public static function getMockedObject($phpunit, $className, $methodsToMock = [], $constructorArgs = null) {

        $mockedObject = self::mockObject($phpunit, $className, $methodsToMock, $constructorArgs);
        $mockedObject = self::mockMethods($mockedObject, $methodsToMock);

        return $mockedObject;
    }

    private static function mockObject($phpunit, $className, $methodsToMock, $constructorArgs = null){
        $mockBuilder = $phpunit->getMockBuilder($className);

        if (count($methodsToMock)) {
            $mockBuilder->setMethods(array_keys($methodsToMock));
        }

        if (!empty($constructorArgs)) {
            $mockBuilder->setConstructorArgs($constructorArgs);
        } else if (is_null($constructorArgs)) {
            $mockBuilder->disableOriginalConstructor();
        }

        $mockedObject = $mockBuilder->getMock();

        $o = new GreetingService();
        foreach ($methodsToMock as $method => $mock) {
            $reflectionMethod = new \ReflectionMethod($mockedObject, $method);
            if ($reflectionMethod->isPrivate()){
                $reflectionMethod->setAccessible(true);
                $mockedObject->$method = $reflectionMethod;
            }
        }
        echo $reflectionMethod->invoke($mockedObject, "Zachary");
        echo $mockedObject->applyGreeting("Zachary");
        die;

        return $mockedObject;
    }

    private static function mockMethods($mockedObject, $methodsToMock){
        foreach ($methodsToMock as $method => $mock) {
            if (array_key_exists('expects', $mock)) {
                $mockedMethod = $mockedObject->expects($mock['expects']);
                $mockedMethod->method($method);
            } else if(array_key_exists('with', $mock) || array_key_exists('willReturn', $mock)){
                $mockedMethod = $mockedObject->method($method);
            }
            if (array_key_exists('with', $mock)) {
                call_user_func_array([$mockedMethod, 'with'], $mock['with']);
            }

            if (array_key_exists('willReturn', $mock)) {
                $mockedMethod->willReturn($mock['willReturn']);
            }
        }
        return $mockedObject;
    }

}
