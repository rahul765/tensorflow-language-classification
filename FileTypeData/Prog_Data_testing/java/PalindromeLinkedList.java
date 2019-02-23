package com.jason.leetcode;

import com.jason.tools.ListNode;

/**
 * Palindrome Linked List
 * https://leetcode.com/problems/palindrome-linked-list/
 *
 *
 * AC Runtime: 532 ms
 */
public class PalindromeLinkedList {

	private static final boolean log = true;
	public static int getLength(ListNode head){
		int len = 0;
		while (head !=null){
			len++;
			head = head.next;
		}
		return len;
	}

	/**
	 * reverse front half
	 * @param head
	 * @return
	 */
	public boolean isPalindrome1(ListNode head) {
		ListNode slowp = head, fastp = head, revp = null;
		while (fastp!=null && fastp.next!=null){
			fastp = fastp.next.next;
			ListNode tmp = slowp.next;
			slowp.next = revp;
			revp = slowp;
			slowp = tmp;
		}
		if (fastp!=null)
			slowp = slowp.next;
		if(log){
			ListNode.printLCLinkedList(slowp);
			ListNode.printLCLinkedList(revp);
		}
		while (slowp!=null && revp!=null ){
			if (slowp.val != revp.val)
				return false;
			slowp = slowp.next;
			revp = revp.next;
		}
		return true;
	}

	public boolean isPalindrome(ListNode head) {
		int len = getLength(head);
		if(log)
			System.out.println("len "+len+",half "+len/2);
		boolean res = true;
		if(len<=0){
			return true;
		}else if(len == 1){
			return true;
		}else if(len == 2){
			if(head.val == head.next.val){
				return true;
			}else{
				return false;
			}
		}else{
			//at leaest 3 element
			ListNode it = head;
			int i = 0;
			int half = len/2;
			ListNode newl = null;
			ListNode newNode = null;
			ListNode pre = null;
			boolean first = false;
			while( it!=null){
				if(i >= half){
					if(!first){
						pre = new ListNode(it.val);
						first = true;
					}else{
						newNode = new ListNode(it.val);
						newNode.next = pre;
						pre = newNode;
					}
				}
				it = it.next;
				i++;
			}
//			if(log)
//				ListNode.printLCLinkedList(pre);

			i=0;
			while(pre!=null && head!=null && i<half){
				if(log)
					System.out.println("New "+pre.val +",Old "+head.val);
				if(pre.val != head.val){
					res = false;
				}
				head = head.next;
				pre = pre.next;
				i++;
			}
		}
		return res;
	}

	public static void main(String[] args) {
		int a[] = {1,2,3,4,5,4,3,2,1};
//		int indexA[] = {1,2};
//		int indexA[] = {1,1,2,1};
		ListNode l = ListNode.initTestList(a);
		PalindromeLinkedList p = new PalindromeLinkedList();
		ListNode.printLCLinkedList(l);
//		System.out.println(p.isPalindrome(l));
		System.out.println(p.isPalindrome1(l));
	}
}
