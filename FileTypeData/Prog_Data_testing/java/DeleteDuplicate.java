package com.jason.leetcode;

import java.util.HashMap;
import java.util.HashSet;

import com.jason.tools.CollectionTool;
import com.jason.tools.ListNode;

/**
 * Given Department Top Three Salaries.sql sorted linked list, delete all duplicates such that each element appear only once.
 * For example,
 * Given 1->1->2, return 1->2.
 * Given 1->1->2->3->3, return 1->2->3.
 * @author Jason Liu
 * 164 / 164 test cases passed.
 * Status: Accepted
 * Runtime: 520 ms
 */
public class DeleteDuplicate {

	public ListNode deleteDuplicates(ListNode head) {
		if (head == null) {
			return null;
		}
		if (head.next == null) {
			return head;
		}
		ListNode itr = head;
		ListNode itrNext = head;
		ListNode itrPrev = head;
		// {1,1}
		// {1,1,1,1}
		//1 1 2
		while (itr.next != null) {
			itrNext = itr.next;
			itrPrev = itr;
			boolean gotEnd = false;
			while (itr.val == itrNext.val) {//itr point to the next neq node
				if (itr.next != null) {
					itr = itr.next;
					itr.next = itrNext.next;
					if (itr.next != null) {
						itrNext = itr.next;
					}else{
						gotEnd = true;
						break;
					}
				}
			}
			if(gotEnd){
				itrPrev.next = null;
				break;
			}else{//itr point to last same number
				itr = itrNext;//itr.next;
				itrPrev.next = itr;
			}
		}
		return head;
	}
	public static void printLinkedList(ListNode head){
		ListNode itr = head;
		while(itr!=null){
			System.out.print(itr.val+",");
			itr = itr.next;
		}
		System.out.println("");
	}
	
	public static void main(String[] args) {
		ListNode head = new ListNode(1);
		
		ListNode a = new ListNode(2);
		head.next = a;
		ListNode b = new ListNode(2);
		a.next = b;
		ListNode c = new ListNode(3);
		b.next = c;
		ListNode d = new ListNode(3);
		c.next = d;
		ListNode e = new ListNode(5);
		d.next = e;
		new DeleteDuplicate().deleteDuplicatesII(head);
		printLinkedList(head);
	}
	
	
	/**
	 * Given Department Top Three Salaries.sql sorted linked list, delete all nodes that have duplicate numbers, leaving only distinct numbers from the original list.
	 * For example,
	 * Given 1->2->3->3->4->4->5, return 1->2->5.
	 * Given 1->1->1->2->3, return 2->3
	 * @param head
	 * @return
	 */
	public ListNode deleteDuplicatesII(ListNode head) {
		if (head == null) {
			return null;
		}
		if (head.next == null) {
			return head;
		}
		ListNode fakeHead = new ListNode(-1);
		fakeHead.next=head;
		HashMap<Integer,ListNode> hm = new HashMap();
		HashSet<Integer> hs = new HashSet();
		ListNode node = fakeHead;
		
		int i=0;
		while( node.next!=null ){
			hm.put(i,node);
			if( node.val == node.next.val ){
				hs.add(i);
				hs.add(i+1);
				i++;
			}else{
				i++;
			}
			//if(i!=0)
			node = node.next;
		}
CollectionTool.printHashSet(hs);
CollectionTool.printHashMap(hm);

System.out.println("BFClean:");
printLinkedList(fakeHead);		

		//int tmpIdx = -1;
		i=0;
		//ListNode prev = fakeHead;
		node = fakeHead;
		while( node.next != null ){
			if( hs.contains( new Integer(i) ) ){
				int pr = i-1;
				int nxt = i+1;
				hm.get(i-1).next = hm.get(i+1);
				System.out.println("pr "+pr+":"+hm.get(pr).val+";i "+i+":"+hm.get(i).val+";nxt "+nxt+":"+hm.get(nxt).val );
			}
			i++;
			node = node.next;
		}
System.out.println("AFClean:");
printLinkedList(fakeHead);
		
		
		/*
		boolean cont = false;
		
		while( node.next!=null){
			cont = false;
			while( hs.contains( new Integer(i) )){
				prev.next= node.next;
				node = node.next;
				i++;
				cont= true;
			}
			if(node==null){
				break;
			}
			prev = node;
			node = node.next;
			if(!cont)
				i++;
		}

		
		/*
		if ( head.next.next  == null){
			if(head.next.val == head.val){
				return null;
			}else{
				return head;
			}
		}
		//first three is not null
		ListNode itrPrev = head;
		ListNode itr = head;
		ListNode itrNext = head;
		// 1 2 2 3 3 4
		while (itr.next != null ) {
			itrPrev = itr;//AUX Pointer,last process
			itr = itr.next;//middle Pointer
			boolean gotEnd = false;
			if(itr.next==null){
				gotEnd= true;
				break;
			}else{
				itrNext = itr.next;
			}
System.out.println("BFClean:");
printLinkedList(head);
			// itrPrev,itr,itrNext all exist
			while (itr.val == itrNext.val) {//itr point to the next neq node
				if (itr.next != null) {
					itr = itr.next;
					itr.next = itrNext.next;
					if (itr.next != null) {
						itrNext = itr.next;
					}else{
						gotEnd = true;
						break;
					}
				}
			}
	
//			if(itrNext == null){
//				gotEnd = true;
//			}
			
			if(gotEnd){
				itrPrev.next = null;
				break;
			}else{//itr point to last same number
				itr = itrNext;//itr.next;
				itrPrev.next = itr;
			}
System.out.println("AFClean:");
printLinkedList(head);
		}*/
		return head;
    }
}
