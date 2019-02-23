package com.interview.algorithms.general;

import junit.framework.TestCase;

public class C1_23A_PostfixExpressionTest extends TestCase {

    public void testTransform() throws Exception {
        String exp = "(2 + 2 * (3 + 2)) * 2";
        String expected = " 2 2  3 2 + *+ 2*";

        String post = C1_23A_PostfixExpression.transform(exp);
        assertEquals(expected, post);
    }

    public void testTransform2() throws Exception {
        String exp = "(23 + 2 * (34.5 + 2)) * 2";
        String expected = " 23 2  34.5 2 + *+ 2*";

        String post = C1_23A_PostfixExpression.transform(exp);
        assertEquals(expected, post);
    }

    public void testcase(){
        String exp = "(1 * 2) + 3";
        String expected = " 1 2 * 3+";
        String post = C1_23A_PostfixExpression.transform(exp);
        assertEquals(expected, post);
    }
}