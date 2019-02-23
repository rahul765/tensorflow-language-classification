package com.interview.leetcode.application;

import junit.framework.TestCase;

import java.util.List;

/**
 * Created_By: stefanie
 * Date: 14-11-26
 * Time: 下午5:28
 */
public class JumpGameTest extends TestCase {
    JumpGame.JumpToZero game = new JumpGame.JumpToZero();
    public void testJumpToZero() throws Exception {
        int[] nums = new int[]{1, 3, 0, 2, 4, 7};
        List<Integer> reachable = game.jumpToZero(nums);
        assertEquals(3, reachable.size());
        assertTrue(reachable.contains(1));
        assertTrue(reachable.contains(3));
        assertTrue(reachable.contains(4));
    }
}
