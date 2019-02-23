package com.interview.leetcode.matrix;

import junit.framework.TestCase;

/**
 * Created_By: stefanie
 * Date: 14-11-15
 * Time: 下午3:53
 */
public class MaximalRectangleTest extends TestCase {
    public void testMaximalRectangle() throws Exception {
//        char[][] matrix = new char[][]{{'0','1'},{'1','0'}};
//        int size = MaximalRectangle.maximalRectangle(matrix);
//        System.out.println(size);
//
//        matrix = new char[][]{{'0','0'}};
//        size = MaximalRectangle.maximalRectangle(matrix);
//        System.out.println(size);

        char[][] matrix = new char[][]{{'0','1'},{'0','1'}};
        int size = MaximalRectangle.maximalRectangle(matrix);
        System.out.println(size);
    }


}
