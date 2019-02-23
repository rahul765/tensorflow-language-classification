package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.util.HashMap;
import java.util.Objects;

/**
 * Easy
 * IsomorphicStrings
 * https://leetcode.com/problems/isomorphic-strings/
 * Runtime: 384 ms
 */
public class IsomorphicStrings {


	public static boolean log = false;

	public  void printi(int[] i){
		CollectionTool.printArray(i);
	}

	public void printc(char[] i){
		CollectionTool.printArray(i);
	}


	public boolean isIsomorphic(String s, String t) {
		if(s==null && t==null){
			return true;
		}
		if(s==null || t==null){
			return false;
		}
		if(s.length()!=t.length()){
			return false;
		}
		int len = s.length();
		HashMap<Character,Integer> hm1 = new HashMap<>();
		HashMap<Character,Integer> hm2 = new HashMap<>();
		char[] c1 = s.toCharArray();
		char[] c2 = t.toCharArray();
		int [] i1 = new int[c1.length];
		int [] i2 = new int[c2.length];
		if(log){
			printc(c1);
			printc(c2);
		}
		int m = 0;
		int n = 0;
		for(int i=0;i<s.length();i++){
			Integer it = hm1.get(c1[i]);
			if( it!=null){

//				System.out.println("not null "+ it);
				i1[i] = it;
			}else{
				m++;
//				System.out.println("null "+ m);
				hm1.put(c1[i],new Integer(m));
				i1[i] = m;
			}

			Integer it2 = hm2.get(c2[i]);
			if( it2!=null){
				i2[i] = it2;
			}else{
				n++;
				hm2.put(c2[i],n);
				i2[i] = n;
			}
		}
		boolean res = true;
		if(log){
			printi(i1);
			printi(i2);
		}
		for(int i=0;i<len;i++){
			if(i1[i]!=i2[i]){
				res = false;
			}
		}
		return res;
	}





}
