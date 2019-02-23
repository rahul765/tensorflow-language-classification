package com.interview.leetcode.dp;

import junit.framework.TestCase;

/**
 * Created_By: stefanie
 * Date: 14-11-17
 * Time: 下午2:15
 */
public class ScrambleStringTest extends TestCase {
    public void testIsScramble() throws Exception {
        assertTrue(ScrambleString.isScramble("rgtae", "great"));
        assertTrue(ScrambleString.isScramble("rgeat", "rgeat"));
        assertFalse(ScrambleString.isScramble("aa", "ab"));
    }
}
