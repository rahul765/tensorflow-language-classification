package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.math.BigDecimal;
import java.util.*;

/**
 * easy
 * Pascal's Triangle II
 * https://leetcode.com/problems/pascals-triangle-ii/
 * Given an index k, return the kth row of the Pascal's triangle.
 * For example, given k = 3,
 * Return [1,3,3,1].
 [1],
 [1,1],
 [1,2,1],
 [1,3,3,1],k=3
 [1,4,6,4,1],k=4
 [1,5,10,10,5,1]k=5
 [1,6,15,20,15,6,1]k=6
 *
 * Note: Could you optimize your algorithm to use only O(k) extra space?
 * Created by JasonLiu on 2015/8/7.
 * getRow AC Runtime: 344 ms
 * getRow AC Runtime: 256 ms
 */
public class PascalsTriangleII {

	List<Integer> getRow1(int rowIndex) {
		List<Integer> res = new ArrayList<>();
		if(rowIndex<=0){
			res.add(1);
			return res;
		}
		Integer[] arr = new Integer[rowIndex+1];
		int i, j;
		for(i=1, arr[0]=1; i<=rowIndex;++i) {
			if(arr[i]==null){
				arr[i] = 0;
			}
			for (j = i; j > 0; --j) {
				System.out.println("i "+i+",j "+j);
				arr[j] += arr[j - 1];
			}
		}
		return Arrays.asList(arr);
	}

	/**
	 * use fomular
	 * @param rowIndex
	 * @return
	 */
	public List<Integer> getRow(int rowIndex) {
		List<Integer> res = new ArrayList<>();
		if(rowIndex<=0){
			res.add(1);
			return res;
		}
		int total = rowIndex+1;
		Integer [] arr = new Integer[total];
		for(int i=0;i<rowIndex/2+1;i++){
			if(i==0){
				arr[i]=arr[rowIndex]=1;
			}else{
//				System.out.println("i "+i+",rowindex "+rowIndex);
				int tmp = calcCnm(i,rowIndex);
				arr[i] = tmp;
				arr[rowIndex-i] = tmp;
//				System.out.println(i+","+arr[i]);
			}
		}
		res = Arrays.asList(arr);
		return res;
	}

	/**
	 * C r,n = n!/(r!*(n-r)!) = n*(n-1)*...(n-r+1)/r!
	 * 1,13 = 13!/(1*12!)
	 * 2,13 = 13!/(2*11!)
	 * @param r
	 * @param n
	 * @return
	 */
	public static int calcCnm(int r,int n){
//		System.out.println( factorial(13) );
//		System.out.println( factorial(12) );
		BigDecimal tmp= new BigDecimal(1);
//		long tmp = 1;//factorial(n)/(factorial(r)*factorial(n-r));
		// 9,18  18!/(9!*9!)
		// 18*...10/9!
		for(int i=n;i>= n-r+1;i--){
			BigDecimal tmpi = new BigDecimal(i);
			tmp = tmp.multiply(tmpi);
//			tmp *= i;
//			System.out.println(i+","+tmp);
		}
//		System.out.println("tmp " + tmp);
		BigDecimal tmpFac = new BigDecimal(factorial(r));
		tmp = tmp.divide(tmpFac);
//		System.out.println("tmp "+tmp);
//		int res = 	(int)tmp;
		int res = tmp.intValue();
//		System.out.println("res "+res);
		return res;
	}

	public static long factorial(int n){
		if(n==0){
			return 1;
		}
		long res = 1;
		for(int i=1;i<=n;i++){
			res *=i;
		}
		return res;
	}


}
