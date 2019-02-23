package com.jason.leetcode;

import java.util.List;

/**
 * Given Department Top Three Salaries.sql triangle, find the minimum path sum from top to bottom. Each step you may move to adjacent numbers on the row below.
 * For example, given the following triangle
	[
	     [2],
	    [3,4],
	   [6,5,7],
	  [4,1,8,3]
	  1 0,1
	  0 -1=>0
	  2 1,2
	]
 * The minimum path sum from top to bottom is 11 (i.e., 2 + 3 + 5 + 1 = 11).
 * Note:Bonus point if you are able to do this using only O(n) extra space, where n is the total number of rows in the triangle.
 * @author Jason Liu
 *
 */
public class MinimumTotal {
	
	/**
	 * use bfs,top2down
	 * add all,down2top
	 * BFS:
	 * 
	 * @param triangle
	 * @return
	 */
	public int minimumTotal(List<List<Integer>> triangle) {
		int len = triangle.size();
		List<Integer> last = triangle.get( triangle.size()-1 ) ;
		int lastLen = triangle.get( triangle.size()-1 ).size();
		int[] sum = new int[ lastLen ];
		List<Integer> LP = null;
		//List<Integer> highP = null;
		
		/*
		1 2 3 4 5 6
		1 2 3 4 5 
		1 2 3 4 
		1 2 3 
		1 2 
		1 1
		*/
		return 0;
	}
	
	public void bfs(List<Integer> l,int level){
		
	}
	
	
	public int sum;

	
	public int findMin(List<Integer> l){
		if(l==null){
			return 0;
		}
		if(l.size()==0){
			return 0;
		}
		if(l.size()==1){
			return l.get(0);
		}
		int min = -1;
		int tmp;
		for(int i=0;i<l.size();i++){
			if(i==0){
				min = i;
			}else{
				tmp = l.get(i);
				if(tmp <min){
					min = tmp;
				}
			}
		}
		return min;
	}
	
	/**
	 * v1 ,miss the 'adjacent' 
	 * @param triangle
	 * @return
	 *
	public int minimumTotal(List<List<Integer>> triangle) {
		if(triangle == null){
			return 0;
		}
		if(triangle.size()==0){
			return 0;
		}
		int sum = 0;
		int tmpMin = 0;
		if(triangle.size()==1){
			return triangle.get(0).get(0);
		}else{
	        for(int i=0;i<triangle.size();i++){
	        	tmpMin= this.findMin(triangle.get(i));
	        	sum += tmpMin;
	        }
		}
		return sum;
    }
	*/
	public static void main(String[] args) {
		// TODO Auto-generated method stub

	}

}
