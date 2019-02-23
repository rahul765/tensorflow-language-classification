package com.jason.leetcode;

import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import static org.junit.Assert.assertEquals;

/**
 * ContainsDuplicateII Tester.
 *
 * @author <Authors name>
 * @version 1.0
 */
public class ContainsDuplicateIITest {
	private static ContainsDuplicateII c = new ContainsDuplicateII();

	@Before
	public void before() throws Exception {
	}

	@After
	public void after() throws Exception {
	}

	/**
	 * Method: containsNearbyDuplicate(int[] nums, int k)
	 */
	@Test
	public void testContainsNearbyDuplicate() throws Exception {
		int[] a1 = {1,2,3,4,4};
		int[] a2 = {1,2,3};
		int[] a3 = {1,2,3,4,6,3};
		int[] a4 = {99,99};
		int[] a5 = {1,0,1,1};
		assertEquals(true,c.containsNearbyDuplicate(a4, 2));
		assertEquals(true,c.containsNearbyDuplicate(a1, 1));
		assertEquals(false,c.containsNearbyDuplicate(a2,1));
		assertEquals(false,c.containsNearbyDuplicate(a3,1));
		assertEquals(false,c.containsNearbyDuplicate(a3,2));
		assertEquals(true,c.containsNearbyDuplicate(a3,3));
		assertEquals(true,c.containsNearbyDuplicate(a5,1));
	}


} 
