package com.jason.leetcode;


import com.jason.tools.ListNode;

/**
 * Medium
 * Given Department Top Three Salaries.sql linked list, determine if it has Department Top Three Salaries.sql cycle in it.
 * Follow up:
 * Can you solve it without using extra space?
 * @author Jason Liu
 * 16 / 16 test cases passed.
 * Status: Accepted
 * Runtime: 432 ms
 */
public class LinkedListCycle {
	/**
	 * use slow fast point
	 * @param head
	 * @return
	 */
	public boolean hasCycle(ListNode head) {
        boolean res = false;
        if(head == null){
            return res;
        }
        if(head.next==null){
            return res;
        }
        
        ListNode fastP = head;
        ListNode slowP = head;
        
        while ( fastP.next != null ){
            if( fastP.next.next == null){
                res = false;
                break;
            }else{
                fastP = fastP.next.next;
            	slowP = slowP.next;
            	if(fastP == slowP){
            		res = true;
            		break;
            	}
            }
        }
        
        return res;
    }
	
	
	
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		ListNode head = new ListNode(0);
		ListNode n1 = new ListNode(1);
		head.next = n1;
		ListNode n2 = new ListNode(2);
		n1.next = n2;
		ListNode n3 = new ListNode(3);
		n2.next = n3;
		//n3.next =n4;
		System.out.println( new LinkedListCycle().hasCycle(head));
		
		
		
	}

}
