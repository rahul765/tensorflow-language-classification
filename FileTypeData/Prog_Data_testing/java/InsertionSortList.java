package com.jason.leetcode;

import com.jason.tools.ListNode;

/**
 * Medium
 * Insertion Sort List
 * https://leetcode.com/problems/insertion-sort-list/
 */
public class InsertionSortList {

	public ListNode insertionSortList(ListNode head) {
		if(head==null||head.next==null){
			return head;
		}
		ListNode newHead = null;
		ListNode curOld = head;

		ListNode curNew = null;
		boolean first = true;
		while(curOld!=null)	{

			ListNode cur = curOld;
			ListNode pre = curOld;
			ListNode minCur = null;
			ListNode minPre = null;
			int min = Integer.MAX_VALUE;
			while(cur!=null){
				if(cur.val<min){
					min = cur.val;
					minCur = cur;
					minPre = pre;
				}
				pre = cur;
				cur = cur.next;
			}
			if(first){
				newHead = minCur;
				first = false;
			}

			ListNode next = minCur.next;
			if(minPre!=null) {
				minPre.next = next;
			}else{
				curOld = next;
			}
			minCur.next = null;

//			curNew.next = minNode;
//			curNew = minNode;
		}
		return newHead;
	}


	public static ListNode delete(ListNode pre,ListNode del){
		ListNode next = del.next;
		if(pre!=null)
			pre.next = next;
		del.next = null;
		return del;
	}



	public static ListNode getMin(ListNode l){
		ListNode cur = l;
		ListNode pre = l;
		ListNode minCur = null;
		ListNode minPre = null;
		int min = Integer.MAX_VALUE;
		while(cur!=null){
			if(cur.val<min){
				min = cur.val;
				minCur = cur;
				minPre = pre;
			}
			pre = cur;
			cur = cur.next;
		}
		return  minCur;
	}


}
