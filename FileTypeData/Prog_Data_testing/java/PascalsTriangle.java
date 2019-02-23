package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.util.LinkedList;
import java.util.List;

/**
 * Easy
 * Pascal's Triangle
 * https://leetcode.com/problems/pascals-triangle/
 * Given numRows, generate the first numRows of Pascal's triangle.
 For example, given numRows = 5,
 Return
 [
 [1],
 [1,1],
 [1,2,1],
 [1,3,3,1],
 [1,4,6,4,1]
 [1,5,10,10,5,1]
 [1,6,15,20,15,6,1]
 ]
 * Created by JasonLiu on 2015/8/7.
 * AC Runtime: 192 ms
 */
public class PascalsTriangle {


	public List<List<Integer>> generate(int numRows) {
		List<List<Integer>>	 res = new LinkedList<>();
		if(numRows<=0){
			return res;
		}
		List<Integer> pre = new LinkedList();
		pre.add(1);
		res.add(pre);
		if(numRows ==1 ){
			return res;
		}
		for(int i=1;i<numRows;i++){
			List<Integer>  next = new LinkedList<Integer>();
			int len = i+1;
			for(int j=0;j<len;j++){
				if(j==0||j==len-1){
					next.add(1);
				}else{
					next.add( pre.get(j-1)+pre.get(j));
				}
			}
//			System.out.println("pre");
//			CollectionTool.printList(pre);
			pre = next;
//			System.out.println("next");
//			CollectionTool.printList(next);
			res.add(next);
		}
		return res;
	}

	public static void main(String[] args) {
		PascalsTriangle p = new PascalsTriangle();
		List<List<Integer>> res = p.generate(5);
		for(int i=0;i<res.size();i++){
			CollectionTool.printList(res.get(i));
		}
	}

}
