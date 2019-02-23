package com.ovoistinov.coding.dojos.arraysplay.strategies;

import com.ovoistinov.coding.dojos.arraysplay.ArrayProcessor;

public class SimpleArrayMissingNumberFinder implements ArrayProcessor {

        public int process(int[] input) {
            int elementSum = 0;
            int idealSum = 1;

            for (int i=0; i < input.length; i++) {
                elementSum += input[i];
                idealSum += (i + 2);
            }

            return idealSum - elementSum;
        }
}
