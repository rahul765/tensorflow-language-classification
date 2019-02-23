package com.jason.leetcode;

import com.jason.tools.CollectionTool;
import com.jason.tools.ListNode;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;

public class ReorderListTest {

	@Before
	public void before() throws Exception {
		r.log = true;
	}

	@After
	public void after() throws Exception {
	}

	public static ReorderList r = new ReorderList();

	@Test
	public void testReorderList() throws Exception {
		//			0 1 2 3 4 5
		int[] a = {1, 2, 3, 4, 5, 6};
		ListNode n1 = ListNode.initTestList(a);
		ListNode.printLCLinkedList(n1);
		r.reorderList(n1);
		ListNode.printLCLinkedList(n1);

		int[] a2 = {1, 2, 3, 4, 5};
		ListNode n2 = ListNode.initTestList(a2);
		ListNode.printLCLinkedList(n2);
		r.reorderList(n2);
		ListNode.printLCLinkedList(n2);

		int[] a3 = {1, 2};
		ListNode n3 = ListNode.initTestList(a3);
		ListNode.printLCLinkedList(n3);
		r.reorderList(n3);
		ListNode.printLCLinkedList(n3);


		int[] a4 = {1, 2, 3};
		ListNode n4 = ListNode.initTestList(a4);
		ListNode.printLCLinkedList(n4);
		r.reorderList(n4);
		ListNode.printLCLinkedList(n4);
	}


} 
