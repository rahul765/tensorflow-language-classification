package com.jason.leetcode;

import com.jason.tools.CollectionTool;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import java.util.LinkedList;
import java.util.List;

import static org.junit.Assert.assertEquals;

/**
 * LargestNumber Tester.
 *
 * @author <Authors name>
 * @version 1.0
 */
public class LargestNumberTest {
	private static LargestNumber l = new LargestNumber();

	@Before
	public void before() throws Exception {
		l.log = true;
	}

	@After
	public void after() throws Exception {
	}

	/**
	 * Method: largestNumber(int[] nums)
	 */
	@Test
	public void testbucketSort() throws Exception {

	}

	/**
	 * Method: getHeadNum(int x)
	 */
	@Test
	public void testGetHeadNum() throws Exception {
//		System.out.println(l.getNum(10));
//		System.out.println(l.getNum(210));
//		System.out.println(l.getNum(3210));

		int[] a1 = {3, 30, 34, 5, 9};
		int[] a2 = {1};
		int[] a3 = {1,1};
		int[] a4 = {1,1,1};
		int[] a5 = {199,21};
		int[] a6 = {1,2,399,300,1024,1999,32};
		int[] a7 =  {13,138};//138 13
		int[] a8 = {13,132};//132 13 13132
		int[] a9 = {131,13};//13 131
		int[] a10 = {13,133};//133 13
		int[] a11 = {13,130};//13 130

		int[] a12 = {12,128,125};
		int[] a13 = {12,128,125,32,343,5};// 5 343 32 128 125 12
		int[] a14 = {12,128,125,135,15,19,151,152};
		int[] a15 =	{12,128,125,35,15,9,351,152};
		int[] a16 =	{12,128,125,12,15,9,351,152};//9 351 152 15 128 125 12 12
		int[] a17 = {0,0,0,0};
//		int[] a11 =	{12,128,12,35,15,9,351,152};
//		System.out.println(l.largestNumber(indexA));

//		assertEquals("9534330",l.largestNumber(a1));
//		assertEquals("21199",l.largestNumber(a4));
//		assertEquals("12812",l.largestNumber(a6));
//		assertEquals("11",l.largestNumber(a3));
//		assertEquals("1",l.largestNumber(a2));
//		assertEquals("13131",l.largestNumber(a13));
//		assertEquals("12812512",l.largestNumber(a7));
//		assertEquals("13131",l.largestNumber(a9));
//		assertEquals("13313",l.largestNumber(a10));
//		assertEquals("13130",l.largestNumber(a11));
//		assertEquals("53433212812512",l.largestNumber(a13));
//		assertEquals("399323002119991024",l.largestNumber(a5));

		//9 351 152 15 128 125 12 12
//		assertEquals("9351152151281251212",l.largestNumber(a16));
		assertEquals("0",l.largestNumber(a17));
//		List ll = l.getNums(a10);
//		System.out.println("init");
//		CollectionTool.printList(ll);
//		System.out.println("-----------");
//		//bucketSort(List<Num> l,int idx,List<Num> res){
//		List res = new LinkedList();
//		l.bucketSort(ll,-1,res);
//		System.out.println("res");
//		CollectionTool.printList(res);

//		System.out.println(l.largestNumber(a1));
	}


} 
