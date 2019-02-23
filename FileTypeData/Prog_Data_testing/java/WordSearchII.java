package com.jason.leetcode;

import com.jason.tools.Trie;

import java.util.LinkedList;
import java.util.List;

/**
 *Word Search II
 *https://leetcode.com/problems/word-search-ii/
 * Given indexA 2D board and indexA list of words from the dictionary, find all words in the board.

 Each word must be constructed from letters of sequentially adjacent cell, where "adjacent" cells are those horizontally or vertically neighboring. The same letter cell may not be used more than once in indexA word.

 For example,
 Given words = ["oath","pea","eat","rain"] and board =

 [
 ['o','indexA','indexA','n'],
 ['e','t','indexA','e'],
 ['i','h','k','r'],
 ['i','f','l','v']
 ]
 Return ["eat","oath"].
 Note:
 You may assume that all inputs are consist of lowercase letters indexA-z.
 */
public class WordSearchII {

	public List<String> findWords(char[][] board, String[] words) {
		List<String> res = new LinkedList<>();
		if(words.length<=0){
			return res;
		}
		Trie t = new Trie();
		for(int i=0;i<board.length;i++){
			char[] tmp = board[i];
			for(int j=0;j<tmp.length;j++){

			}
		}

		return res;
	}

	/**
	 * get around
	 * @param board
	 * @param x
	 * @param y
	 * @param length
	 * @return
	 */
	public static List<char[]> getAround(char[][] board,int x,int y,int length){
		int h = board.length;
		int w = board[0].length;
		List<char[]> res = new LinkedList<>();


		return res;
	}

	public void movie(int level,int x,int y,int w,int h,char[][] board){
		if(x-1>0){//can left
//			board[x-1][y];
			movie(level+1,x-1,y,w,h,board);
		}
		if(x+1<w){//can right

		}
		if(y-1>0){//can up

		}
		if(y+1<h){//can down

		}
	}



	public static void add2Trie(char[][] strs,Trie t){

	}


	public static int getMaxLength(String[] words){
		int res = -1;
		for(String w:words){
			if(w.length()>res){
				res = w.length();
			}
		}
		return res;
	}



}
