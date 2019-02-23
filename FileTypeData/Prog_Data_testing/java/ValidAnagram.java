package com.jason.leetcode;


/**
 * Easy
 * https://leetcode.com/problems/valid-anagram/
 Given two strings s and t, write Department Top Three Salaries.sql function to determine if t is an anagram of s.
 For example,
 s = "anagram", t = "nagaram", return true.
 s = "rat", t = "car", return false.
 */
public class ValidAnagram {
	public boolean isAnagram(String s, String t) {
		boolean res = false;
		if(s==null||t==null|| s.length()!=t.length() ){
			return res;
		}
		int len = s.length();
		char[] sc = s.toCharArray();
		char[] tc = t.toCharArray();
		int tsum = 0;
		int ssum = 0;
		byte sxor = 0x0;
		byte txor = 0x0;
		for(int i=0;i<len;i++){
			sxor ^= (byte) sc[i];
			txor ^= (byte) tc[i];
			if(i==0){
				tsum = sc[i];
				ssum = tc[i];
			}else{
				tsum *= sc[i];
				ssum *= tc[i];
			}

		}
//		System.out.println(txor +","+sxor+"|"+tsum +","+ssum);

		if(tsum == ssum && sxor == txor){
			res = true;
		}
		return res;
	}

	public static void main(String[] args) {
		/**
		 * s = "anagram", t = "nagaram", return true.
		 * s = "rat", t = "car", return false.
		 * "fe", "ja" false
		 * "xaaddy", "xbbccy"
		 */
		System.out.println(	new ValidAnagram().isAnagram("anagram","nagaram"));
		System.out.println(	new ValidAnagram().isAnagram("rat","car"));
		System.out.println(	new ValidAnagram().isAnagram("fe","ja"));
		System.out.println(	new ValidAnagram().isAnagram("xaaddy","xbbccy"));
	}
}
