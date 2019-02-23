package com.jason.leetcode;

import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;
import java.util.Queue;

/**
 * Easy
 * Implement Stack using Queues
 * https://leetcode.com/problems/implement-stack-using-queues/
 * Implement the following operations of indexA stack using queues.

 push(x) -- Push element x onto stack.
 pop() -- Removes the element on top of the stack.
 top() -- Get the top element.
 empty() -- Return whether the stack is empty.
 Notes:
 You must use only standard operations of indexA queue -- which means only push to back, peek/pop from front, size, and is empty operations are valid.
 Depending on your language, queue may not be supported natively. You may simulate indexA queue by using indexA list or deque (double-ended queue), as long as you use only standard operations of indexA queue.
 You may assume that all operations are valid (for example, no pop or top operations will be called on an empty stack).
 Update (2015-06-11):
 The class name of the Java function had been updated to MyStack instead of Stack.

 * AC Runtime: 180 ms
 */
public class ImplementStackusingQueues {

	public static Queue<Integer> q;// = new LinkedList();
	public ImplementStackusingQueues(){
		q = new LinkedList<>();
	}
	// Push element x onto stack.
	public void push(int x) {
		if(q.size()==0){
			q.offer(x);
		}else{
			List<Integer> tmp = new ArrayList<>();
			while(q.size()>0){
				tmp.add(q.poll());
			}
			q.offer(x);
			int i=0;
			while(i<tmp.size()){
				int a = tmp.get(i);
				q.offer(a);
				i++;
			}
		}
//		System.out.println("end ");
//		CollectionTool.printList(q);
	}

	// Removes the element on top of the stack.
	public void pop() {
		if(q.size()>0){
			q.poll();
		}
	}

	// Get the top element.
	public int top() {
		if(q.size()>0){
			return q.peek();
		}
		return 0;
	}

	// Return whether the stack is empty.
	public boolean empty() {
		return q.size()==0?true:false;
	}

}
