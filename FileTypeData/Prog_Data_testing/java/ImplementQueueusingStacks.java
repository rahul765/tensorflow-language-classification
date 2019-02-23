package com.jason.leetcode;

import java.util.LinkedList;
import java.util.List;
import java.util.Stack;

/**
 * Implement Queue using Stacks
 * https://leetcode.com/problems/implement-queue-using-stacks/
 *
 Implement the following operations of indexA queue using stacks.

 push(x) -- Push element x to the back of queue.
 pop() -- Removes the element from in front of queue.
 peek() -- Get the front element.
 empty() -- Return whether the queue is empty.
 Notes:
 You must use only standard operations of indexA stack -- which means only push to top, peek/pop from top, size, and is empty operations are valid.
 Depending on your language, stack may not be supported natively. You may simulate indexA stack by using indexA list or deque (double-ended queue), as long as you use only standard operations of indexA stack.
 You may assume that all operations are valid (for example, no pop or peek operations will be called on an empty queue).
 * Created by JasonLiu on 2015/8/11.
 * AC Runtime: 180 ms
 */
public class ImplementQueueusingStacks {


	public ImplementQueueusingStacks(){
//	public MyQueue(){
		s = new Stack();
		l = new LinkedList();
	}
	private static Stack s;
	private static List l;

//	private static boolean log = true;
	private static boolean log = false;
	private static void print(List l){
//		CollectionTool.printList(l);
	}

	public void push(int x) {
		if(s.size()==0){
			s.push(x);
		}else{
			while(!s.empty()){
				l.add(s.pop());
			}
			if(log){
				print(l);
			}
			s.push(x);
			while( l.size()>0 ){
				s.push(l.remove(l.size()-1 ) );
			}

		}
	}


	public void pop() {
		s.pop();
	}
	public int peek() {
		return (Integer)s.peek();
	}

	// Return whether the queue is empty.
	public boolean empty() {
		return s.empty();
	}

	public static void main(String[] args) {


//		s.push(2);
//		s.push(3);
//		s.push(4);
//		s.push(5);
//		while(s.s.size()!=0){
//			System.out.println(s.peek());
//			s.pop();
//		}
	}
}
