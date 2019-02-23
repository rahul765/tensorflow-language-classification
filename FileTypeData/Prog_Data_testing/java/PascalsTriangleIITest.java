package com.jason.leetcode;

import com.jason.tools.CollectionTool;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import java.util.List;

/**
 * PascalsTriangleII Tester.
 *
 * @author <Authors name>
 * @version 1.0
 * @since <pre>���� 27, 2015</pre>
 */
public class PascalsTriangleIITest {

	@Before
	public void before() throws Exception {
	}

	@After
	public void after() throws Exception {
	}

	public static PascalsTriangleII p = new PascalsTriangleII();
	@Test
	public void test() throws Exception {

		//		System.out.println(factorial(5));
//		System.out.println(calcCnm(1,4));

//k=13		[1,13,78,286,715,1287,1716,1716,1287,715,286,78,13,1]
//		System.out.println(calcCnm(2, 13));
//			[1,18,153,816,3060,8568,18564,31824,43758,1276, 43758,31824,18564,8568,3060,816,153,18,1]
//Expected:
//k=18		[1,18,153,816,3060,8568,18564,31824,43758,48620,43758,31824,18564,8568,3060,816,153,18,1]
//`	 		1,18,153,816,3060,8568,18564,31824,43758,48620,43758,31824,18564,8568,3060,816,153,18,1,
//		[1,30,435,4060,27405,142506,593775,2035800,5852925,14307150,30045015,54627300,86493225,119759850,-66175233,-54279,-66175233,119759850,86493225,54627300,30045015,14307150,5852925,2035800,593775,142506,27405,4060,435,30,1]
//		[1,30,435,4060,27405,142506,593775,2035800,5852925,14307150,30045015,54627300,86493225,119759850,145422675,155117520,145422675,119759850,86493225,54627300,30045015,14307150,5852925,2035800,593775,142506,27405,4060,435,30,1]

		List<Integer> l = p.getRow1(30);
		CollectionTool.printList(l);
//		System.out.println(factorial(9));
//		System.out.println(calcCnm(9,18));
	}


} 
