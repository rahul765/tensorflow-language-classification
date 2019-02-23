<?php

namespace spec\me\adamcameron\fizzBuzzChallenge;

use me\adamcameron\general\fizzBuzzChallenge\FizzBuzzNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FizzBuzzNumberSpec extends ObjectBehavior
{
    function it_isInitializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(FizzBuzzNumber::class);
    }

    function it_isInitializableWithFizz() {
        $this->beConstructedWith("Fizz");
        $this->shouldHaveType(FizzBuzzNumber::class);
    }

    function it_isInitializableWithBuzz() {
        $this->beConstructedWith("Buzz");
        $this->shouldHaveType(FizzBuzzNumber::class);
    }

    function it_isInitializableWithFizzBuzz() {
        $this->beConstructedWith("FizzBuzz");
        $this->shouldHaveType(FizzBuzzNumber::class);
    }

    function it_isInitializableWithOtherInteger() {
        $this->beConstructedWith(2);
        $this->shouldHaveType(FizzBuzzNumber::class);
    }

    function it_isNotInitializableWithMultipleOfThree() {
        $this->beConstructedWith(6);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_isNotInitializableWithMultipleOfFive() {
        $this->beConstructedWith(10);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_returnsFizzWhenCreatingFromIntegerWithMultipleOfThree() {
        $this->beConstructedWith(1);
        $this::fromInteger(12)->shouldHaveType(FizzBuzzNumber::class);
        $this::fromInteger(12)->__toString()->shouldBe("fizz");
    }

    function it_returnsBuzzWhenCreatingFromIntegerWithMultipleOfFive() {
        $this->beConstructedWith(1);
        $this::fromInteger(20)->shouldHaveType(FizzBuzzNumber::class);
        $this::fromInteger(20)->__toString()->shouldBe("buzz");
    }

    function it_returnsFizzBuzzWhenCreatingFromIntegerWithMultipleOfFifteen() {
        $this->beConstructedWith(1);
        $this::fromInteger(30)->shouldHaveType(FizzBuzzNumber::class);
        $this::fromInteger(30)->__toString()->shouldBe("fizzbuzz");
    }

    function it_returnsAStringFromToString() {
        $this->beConstructedWith(1);
        $this->__toString()->shouldBeString();
    }
}
