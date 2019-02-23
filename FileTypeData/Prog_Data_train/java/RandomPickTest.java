package com.interview.algorithms.random;

import com.interview.utils.ConsoleWriter;
import junit.framework.TestCase;

public class RandomPickTest extends TestCase {

    public void testPick() throws Exception {
        RandomPick<Integer> picker = new RandomPick<>();
        Integer[] array = new Integer[5];
        for(int i = 0; i < array.length; i++) array[i] = i;
        for(int i = 0; i < 10; i++){
            Integer[] elements = new Integer[3];
            picker.pick(array, elements);
            ConsoleWriter.printIntArray(elements);
        }
    }
}