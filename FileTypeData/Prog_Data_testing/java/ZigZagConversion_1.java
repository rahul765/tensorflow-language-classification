package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;
import java.util.Stack;

/**
 * Easy
 * ZigZagConversion
 * AC Runtime: 500 ms
 */
public class ZigZagConversion {
	public static boolean log = false;
	private List<String> l = new LinkedList<>();
	public static class StackQueue{
		List<String> l = new LinkedList<>();
		public void push(String str){
			l.add(str);
		}
	}

	public List<String> init(int numRows){
		List<String> tmp = new ArrayList<>();
		for(int i=0;i<numRows;i++){
			tmp.add("");
		}
		return tmp;
	}

	public String convert(String s, int numRows) {
		if(numRows<=1){
			return s;
		}
		int len = s.length();
		int oneLoopCnt = 2*numRows-2;
		if(log) {
			System.out.println("len " + len + ",numRows " + numRows + ",oneLoopCnt " + oneLoopCnt);
//			System.out.println(len / numRows);
		}
		char[] sarr = s.toCharArray();
		List<String> tmp = init(numRows);
		int idx = 0;
		for(int i=0;i<len;){
			if(log)
				System.out.println("i "+i);
//			int j=i%len;
//			System.out.println("j"+j);
			for(int j=0;j<oneLoopCnt;j++){
				tmp.set(idx,tmp.get(idx) + sarr[i]);
				if( j<numRows-1) {
					idx++;
					if(log)
						System.out.println( "down,idx "+ idx );
				}else{
					idx--;
					if(log)
						System.out.println( "up,idx "+idx );
				}
				i++;
				if(i==len){
					break;
				}
			}
		}
//		CollectionTool.printList(tmp);
		//PAYPALISHIRING
		//PAHN APLSIIG YIR

		String res ="";
		for(int i=0;i<tmp.size();i++){
			res +=tmp.get(i);
		}
		return res;
	}
}
