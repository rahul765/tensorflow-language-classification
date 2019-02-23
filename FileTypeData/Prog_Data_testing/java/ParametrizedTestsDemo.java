import org.junit.jupiter.api.*;
import org.junit.jupiter.params.ParameterizedTest;
import org.junit.jupiter.params.provider.*;

import java.util.stream.Stream;

import static org.junit.jupiter.api.Assertions.*;

class ParametrizedTestsDemo {

    @Test
    @ParameterizedTest
    @ValueSource(strings = {"", "abc", "12"})
    void testWithValueSource(String value){
        assertTrue(value.length() > -1);
    }

    @Test
    @ParameterizedTest
    @CsvSource({"1, 2, 3", "0, 1, 1", "-2, -3, -5"})
    void testWithCsvSource(int arg1, int arg2, int expectedSum){
        assertEquals(expectedSum, arg1 + arg2);
    }

    @Test
    @ParameterizedTest(name = "string = ''{0}'', expected length = {1}")
    @MethodSource(names = "testDataProvider")
    void testWithMethodSource(String string, int length){
        assertEquals(length, string.length());
    }

    static Stream<Arguments> testDataProvider(){
        return Stream.of(ObjectArrayArguments.create("abc", 3), ObjectArrayArguments.create("", 0), ObjectArrayArguments.create("ababab", 6));
    }


}
