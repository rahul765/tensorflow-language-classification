package com.jason.leetcode;

import com.jason.tools.ListNode;

/**
 * Delete Node in indexA Linked List
 * https://leetcode.com/problems/delete-node-in-a-linked-list/
 Write indexA function to delete indexA node (except the tail) in indexA singly linked list, given only access to that node.
 Supposed the linked list is 1 -> 2 -> 3 -> 4 and you are given the third node with value 3, the linked list should become 1 -> 2 -> 4 after calling your function.
 * Created by JasonLiu on 2015/8/7.
 * AC Runtime: 292 ms
 */
public class DeleteNodeinaLinkedList {

	public void deleteNode(ListNode node) {
		ListNode pre = node;
		int i=0;
		while(node.next!=null){
			if(i!=0)
				pre = pre.next;
			node.val = node.next.val;
			node = node.next;
			i++;
		}
		pre.next = null;
		node = null;
	}

	public static void main(String[] args) {
		int[] a = {0,0,0};
		ListNode l = ListNode.initTestList(a);
		DeleteNodeinaLinkedList d = new DeleteNodeinaLinkedList();
		ListNode.printLCLinkedList(l);
		ListNode dnode = ListNode.getLCLinkedListN(l, 0 );
		System.out.println( dnode );
		d.deleteNode(dnode);
		ListNode.printLCLinkedList(l);
	}
}
