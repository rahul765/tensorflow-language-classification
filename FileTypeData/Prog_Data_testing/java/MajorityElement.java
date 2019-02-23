package com.jason.leetcode;

import java.util.HashMap;
import java.util.Map;

/**
 * Easy
 * Majority Element
 * Easy
 * https://leetcode.com/problems/majority-element/
 Given an array of size n, find the majority element. The majority element is the element that appears more than ⌊ n/2 ⌋ times.
 You may assume that the array is non-empty and the majority element always exist in the array.
 */
public class MajorityElement {
	public int majorityElement(int[] nums) {
		int majority = 0;
		int len = nums.length;
		int times = len/2;
		HashMap<Integer,Integer> hm = new HashMap<>();
		for(int i:nums){
			if(hm.get(i)!=null){
				hm.put(i, hm.get(i)+1);
			}else{
				hm.put(i,1);
			}
		}
		for(Map.Entry<Integer,Integer> e:hm.entrySet()){
			if( e.getValue() > times ){
				majority = e.getKey();
				break;
			}
		}
		return majority;
	}

	public static void main(String[] args) {
		int[] a = {1,2,2,2,6};

		System.out.println(new MajorityElement().majorityElement(a));

	}
}
