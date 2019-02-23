package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.util.*;

/**
 * Medium
 * Largest Number
 * https://leetcode.com/problems/largest-number/
 * Runtime: 364 ms
 */
public class LargestNumber {


	public static void print(List l){
//		CollectionTool.printList(l);
	}
	public static boolean log = false;


	public static class Num{
		public int val;
		public int digit;
		public int headidx;
		public char[] arr;
		public char head;
		@Override
		public String toString() {
			return "Num{" +
					"val=" + val +
					", digit=" + digit +
					", headidx=" + headidx +
					", arr=" + Arrays.toString(arr) +
					", head=" + head +
					'}';
		}
	}
	/*
	public static Comparator headCharD = new Comparator<Num>() {
		public int compare(Num o1, Num o2) {
			return o2.head-o1.head;
		}
	};*/

	public static Comparator numDESC = new Comparator<Num>() {
		public int compare(Num o1, Num o2) {
			int res=0;
			for(int i=0;i<Math.min(o1.arr.length,o2.arr.length);i++){
				if( o1.arr[i]<o2.arr[i] ){
					res = 1;
					break;
				}else if( o1.arr[i]>o2.arr[i] ){
					res = -1;
					break;
				}
			}
			return res;
		}
	};

	public static int maxIdx =0;

	public static List<Num> getNums(int[] nums){
		List<Num> l = new LinkedList<>();
		for(int i=0;i<nums.length;i++){
			Num tmp = getNum(nums[i]);
			if(tmp.digit> maxIdx){
				maxIdx = tmp.digit;
			}
			l.add(tmp);
		}
		return l;
	}

	/**
	 * bucket sort
	 * @param nums
	 * @return
	 */
	public String largestNumber(int[] nums) {
		if (nums.length <= 0) {
			return "";
		}
		if (nums.length == 1) {
			return nums[0] + "";
		}
		List<Num> lres = new LinkedList();
		List l = getNums(nums);
		bucketSort(l, -1, lres);
		StringBuffer res = new StringBuffer();
		StringBuffer res2 = null;
		if(log)
			res2=new StringBuffer();
		int zeroidx = 0;
		for(int i=zeroidx;i<lres.size();i++){
			if(i==zeroidx && lres.get(i).val ==0 ){
				zeroidx++;
				continue;
			}
			res.append(lres.get(i).val);
			if (log)
				res2.append(lres.get(i).val+" ");
		}
		if(res.length()==0){
			res.append("0");
		}
		if(log)
			System.out.println(res2);

		return res.toString();
	}

	public static void bucketSort(List<Num> l,int idx,List<Num> res){//int maxidx,List<Num> res){
		if(log){
			print(l);
		}
		idx++;
		TreeMap<Character,List> bucket = new TreeMap();
		for(int i=0;i<l.size();i++){
			Num n = l.get(i);
			if( idx>=n.digit){
				n.head = n.arr[0];
			}else{
				n.head=n.arr[idx];
			}
			n.headidx = idx;
			List tmpl =  bucket.get(n.head);
			if(tmpl==null){
				tmpl = new LinkedList();
			}
			tmpl.add(n);
			bucket.put(n.head , tmpl);
		}
		if(log)
			System.out.println("after "+bucket);
		while(bucket.size()>0){
			List<Num> ll = (List) bucket.pollLastEntry().getValue();
			if(log){
				System.out.println("poll");
				print(ll);
			}
			if(ll.size()==1 || idx == maxIdx){
				if(ll.size()==1){
					res.add( ll.get(0) );
				}else {
					for(int i=0;i<ll.size();i++){
						Num n = ll.get(i);
						if(n.digit<n.headidx){
							//low length
							char[] newarr = new char[n.arr.length*2];
							System.arraycopy(n.arr,0,newarr,0,n.arr.length);
							System.arraycopy(n.arr,0,newarr,n.arr.length,n.arr.length);
							n.arr = newarr;
						}else{//n.digit-1<=n.headidx
							//high length
							char[] newarr = new char[n.arr.length+1];
							System.arraycopy(n.arr,0,newarr,0,n.arr.length);
							newarr[n.arr.length] = n.arr[0];
							n.arr = newarr;
						}
					}
					if(log){
						System.out.println("after fill");
						print(ll);
					}
					Collections.sort(ll, numDESC);
					if(log){
						System.out.println("after sort");
						print(ll);
					}
					for (Num n : ll) {
						res.add(n);
					}
				}
			}else if(ll.size()>1){
				bucketSort(ll,idx,res);
			}
		}
		if(idx == maxIdx){
			return;
		}
	}

	public static Num getNum(int x){
		Num res= new Num();
		res.arr = String.valueOf(x).toCharArray();
		int digit = 1;
		res.val = x;
		res.headidx = 0;
		if(x<10) {
			res.digit = digit;
			res.head = (char)x;
		}else{
			while(x>0){
				x=x/10;
				digit++;
				if(x<10){
					break;
				}
			}
			res.digit = digit;
			res.head = res.arr[res.headidx];
		}
		return res;
	}


	/**
	 * use string sort
	 * @param nums
	 * @return
	 */
	public String largestNumber1(int[] nums) {
		String[] numbers = new String[nums.length];
		for(int i = 0; i < nums.length; i++) {
			numbers[i] = String.valueOf(nums[i]);
		}
		Arrays.sort(numbers, new Comparator<String>() {
			public int compare(String o1, String o2) {
				String s1 = o2 + o1;
				String s2 = o1 + o2;
				for(int i=0; i<s1.length(); i++) {
					if(s1.charAt(i) > s2.charAt(i))
						return 1;
					if(s1.charAt(i) < s2.charAt(i))
						return -1;
				}
				return 0;
			}
		});
		StringBuilder sb = new StringBuilder();
		for(String s : numbers)
			sb.append(s);
		String res = sb.toString();
		return res.charAt(0) == '0' ? "0" : res;
	}



}
