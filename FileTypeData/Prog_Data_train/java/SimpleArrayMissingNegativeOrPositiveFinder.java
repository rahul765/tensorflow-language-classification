package com.ovoistinov.coding.dojos.arraysplay.strategies;

import com.ovoistinov.coding.dojos.arraysplay.ArrayProcessor;

import java.util.Arrays;
import java.util.Collections;

public class SimpleArrayMissingNegativeOrPositiveFinder implements ArrayProcessor {
    @Override
    public int process(int[] input) {
        int elementSum = 0;

        int minimumElement = findMinimumElement(input);
        int idealSum = minimumElement;
        int lastIndex = 0;

        for (int i=0; i < input.length; i++) {
            elementSum += input[i];
            idealSum += (i + minimumElement + 1);
            lastIndex = i;
        }

        if (idealSum - elementSum - lastIndex - 1 == minimumElement) {
            throw new IllegalArgumentException("There are no missing numbers");
        }

        return idealSum - elementSum;
    }

    private int findMinimumElement(int[] input) {
        Arrays.sort(input);
        return input[0];
        // TODO: nicer way to find min probably using streams
    }
}
