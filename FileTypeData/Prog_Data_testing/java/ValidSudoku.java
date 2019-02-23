package com.jason.leetcode;

import com.jason.tools.CollectionTool;

import java.util.HashMap;
import java.util.HashSet;
import java.util.Set;

/**
 * Easy
 * ValidSudoku
 * https://leetcode.com/problems/valid-sudoku/
 * Runtime: 360 ms
 */
public class ValidSudoku {

	public static boolean log = false;

	public static void prints(Set s){
		CollectionTool.printSet(s);
	}

	public boolean isValidSudoku(char[][] board) {
		boolean res = true;
		boolean chkCol = true;
		int len = board.length;
		for(int i=0;i<len;i++){
			res = chkRow(board[i]);//chk row
			if(!res){
				break;
			}
			for (int j = 0; j < len; j++) {
				if(j%3==0 && i%3 ==0){
					if(log)
						System.out.println("chkbox i"+i+",j "+j);
					res = chkBox(board, i, j);
					if(!res)
						break;
				}
				if(chkCol) {//chk column
					res = chkCol(board, j);
					if(log)
						System.out.println("chk col "+res);
					if(!res)
						break;

				}
			}
			if(chkCol)
				chkCol = false;
			if(!res){
				break;
			}
		}
		return res;
	}

	public static boolean chkRow(char[] arr){
		Set<Character> set = new HashSet<>();
		boolean res = true;
		for(int i=0;i<arr.length;i++){
			if(arr[i]!='.') {
				if (set.contains(arr[i])) {
					if(log) {
						prints(set);
						System.out.println(arr[i]);
					}
					res = false;
					break;
				} else {
					set.add(arr[i]);
				}
			}
		}
		return res;
	}

	public static boolean chkCol(char[][] arr,int col){
		boolean res = true;
		Set<Character> set = new HashSet<>();
		for(int i=0;i<arr.length;i++){
			if(arr[i][col]!='.') {
				if (set.contains(arr[i][col])) {
					if(log){
						prints(set);
						System.out.println("i "+i+",col "+col+","+arr[i][col]);
					}
					res = false;
					break;
				} else {
					set.add(arr[i][col]);
				}
			}
		}
		return res;
	}

	public static boolean chkBox(char[][] arr,int x,int y){
		boolean res = true;
		Set<Character> set = new HashSet<>();
		for(int i=x;i<x+3;i++){

			for(int j=y;j<y+3;j++){
				if(arr[i][j]!='.')
				if(set.contains(arr[i][j])){
					res = false;
					break;
				}else{
					set.add(arr[i][j]);
				}
			}
			if(!res)
				break;
		}
		return res;
	}

}
