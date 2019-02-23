package com.interview.leetcode.dp;

import junit.framework.TestCase;

/**
 * Created_By: stefanie
 * Date: 14-11-26
 * Time: 上午8:55
 */
public class MinimalAdjustCostTest extends TestCase {
    public void testMinAdjustmentCost() throws Exception {
        OneSequenceDP.MinimalAdjustCost finder = new OneSequenceDP.MinimalAdjustCost();
        int[] array = new int[]{12,3,7,4,5,13,2,8,4,7,6,5,7};

        assertEquals(19, finder.MinAdjustmentCost(array, 2));
    }
}
