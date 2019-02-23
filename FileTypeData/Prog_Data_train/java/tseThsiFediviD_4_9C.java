package com.interview.algorithms.design;

import junit.framework.TestCase;

/**
 * Created_By: stefanie
 * Date: 14-8-30
 * Time: 下午8:27
 */
public class C9_4_DivideFishTest extends TestCase {
    public void testMinAmout() throws Exception {
        int amount = C9_4_DivideFish.minAmout(3);
        assertEquals(3121, amount);
    }
}
