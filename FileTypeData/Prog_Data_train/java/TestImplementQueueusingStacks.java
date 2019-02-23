package com.jason.leetcode;

import org.junit.Test;

import static org.junit.Assert.assertEquals;

/**
 * Created by JasonLiu on 2015/8/11.
 */
public class TestImplementQueueusingStacks {

	private ImplementQueueusingStacks s = new ImplementQueueusingStacks();

	@Test
	public void testCase1(){
		s.push(1);
		s.pop();
		assertEquals(s.empty(), true);
	}

}
