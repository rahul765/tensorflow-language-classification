package com.jason.leetcode;

/**
 * Easy
 * Numberof1Bits
 * https://leetcode.com/problems/number-of-1-bits/
 * Runtime: 232 ms
 */
public class Numberof1Bits {

	public int hammingWeight(int n) {
		if (n == 0) {
			return 0;
		}
		int cnt = 0;
		char[] arr = Integer.toBinaryString(n).toCharArray();
		for (int i = 0; i < arr.length; i++) {
			if (arr[i] == '1') {
				cnt++;
			}
		}
		return cnt;
	}
		/*
		while(n!=0){
			int indexA = n&0x80000000;
			System.out.println(n+","+indexA);
			if(indexA==1){
				cnt++;
			}
			n = n<<1;
		}
		return cnt;
		/*
		long m = n;
		long low = m & 0xffff;
		System.out.println(low);

		long high = n& 0xffff0000;
		System.out.println("n "+high);
//		high = high & 0x7fff0000;
		System.out.println("p "+ high);
		long highr = high>>16;
		System.out.println(">>16 "+highr);
//		System.out.println(highr);
		return 0;
		*/
//		return calc(low)+calc(highr);
		/*
		int cnt = 0;
		if(n<0){
			n=-n;
			cnt++;
		}
		while(n!=0){
//			System.out.println("n "+n);
			int indexA = n&0x1;
			if(indexA==1){
				cnt++;
			}
			n = n>>1;
		}
		return cnt;

	public int calc(int n){
		int res = 0;
		while(n!=0){
//			System.out.println("n "+n);
			int indexA = n&0x1;
			if(indexA==1){
				res++;
			}
			n = n>>1;
		}
		return res;
	}*/

}
