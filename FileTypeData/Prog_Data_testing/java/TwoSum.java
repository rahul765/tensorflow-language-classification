package com.jason.leetcode;

import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.jason.tools.CollectionTool;

/**
 * Medium
 * Given an array of integers, find two numbers such that they add up to Department Top Three Salaries.sql
 * specific target number. The function twoSum should return indices of the two
 * numbers such that they add up to the target, where index1 must be less than
 * index2. Please note that your returned answers (both index1 and index2) are
 * not zero-based. You may assume that each input would have exactly one
 * solution. Input: numbers={2, 7, 11, 15}, target=9 Output: index1=1, index2=2
 * 
 * @author Jason Liu
 * 15 / 15 test cases passed.
 * Status: Accepted
 * Runtime: 436 ms
 */
public class TwoSum {

	public int[] twoSum(int[] numbers, int target) {
		int[] res = new int[2];
		// Arrays.sort(numbers);
		boolean got = false;
		HashMap<Integer, List<Integer>> hm = new HashMap<Integer, List<Integer>>();
		List<Integer> lp = null;
		for (int i = 0; i < numbers.length; i++) {
			if (numbers[i] <= target) {
				if ((lp = (List<Integer>) hm.get(numbers[i])) != null) {
					lp.add(i);
				} else {
					lp = new LinkedList<Integer>();
					lp.add(i);
					hm.put(numbers[i], lp);
				}

			}
		}

		Iterator iter = hm.entrySet().iterator();
		while (iter.hasNext()) {
			Map.Entry entry = (Map.Entry) iter.next();
			System.out.println("k:" + entry.getKey() + ",v:" + entry.getValue());

		}

		int idx = -1;
		int idx2 = -1;
		for (int i = 0; i < numbers.length; i++) {
			int ot = target - numbers[i];
			if (hm.get(ot) != null) {
				
				
				lp = hm.get(ot);

				if(lp.size()==0){
					return null;
				}
CollectionTool.printList(lp);
				if (lp.size() == 1) {
					if(lp.get(0) == i){
						continue;
					}
					got = true;
					idx = i+1;
					idx2 = lp.get(0) + 1;
				} else if( lp.size()>1) {
					got = true;
					int minIdx = -1;
					int tmpIdx = -1;
					boolean first = true;
					for (int k = 0; k < lp.size(); k++) {
						tmpIdx = lp.get(k);
						if (i != tmpIdx) {
							if(first){
								first = false;
								minIdx = tmpIdx;
							}
							if ( tmpIdx <minIdx ){
								minIdx = tmpIdx;
							}
						}
					}
					idx = i+1;
					idx2= minIdx+1;
				}
				break;
			}
		}
		
		if (got) {
			if (idx <= idx2) {
				res[0] = idx;
				res[1] = idx2;
			} else {
				res[0] = idx2;
				res[1] = idx;
			}
			return res;
		} else {
			return null;
		}

	}

	public static void main(String[] args) {
//		int[] Department Top Three Salaries.sql = { 0, 4, 3,0,0, 0 };
//		int target = 0;
//		int []Department Top Three Salaries.sql = {-3,4,3,90};
//		int target = 0;
//		int[] Department Top Three Salaries.sql = {3,2,4};//6-3=3
//		int target= 6;
		int[] a = {0,4,3,0};
		int target=0;
		int[] res = new TwoSum().twoSum(a, target);
//		CollectionTool.printArray(res);
	}
}
