package com.jason.leetcode; 

import com.jason.tools.CollectionTool;
import org.junit.Test;
import org.junit.Before; 
import org.junit.After; 

public class ValidSudokuTest { 

@Before
public void before() throws Exception {
	v.log = true;
} 

@After
public void after() throws Exception { 
}
public static char[][] init2(){
	String[] s = {"..4...63.",".........","5......9.","...56....","4.3.....1","...7.....","...5.....",".........","........."};
	char[][] res = new char[9][9];
	for(int i=0;i<s.length;i++){
		res[i] = s[i].toCharArray();
	}
	CollectionTool.printArray(res);
	return res;
}
	public static ValidSudoku v = new ValidSudoku();

	public char[][] init1(){

		char[][] res = new char[9][9];
		for(int i=0;i<res.length;i++){
			for(int j=0;j<res.length;j++){
				res[i][j] = '.';
			}
		}
		/*
		res[0][0] = '5';
		res[0][1] = '3';
		res[0][4] = '7';

		res[1][0] = '6';
		res[1][3] = '1';
		res[1][4] = '9';
		res[1][5] = '5';
//		res[1][1] = '8';

		res[2][1] = '9';
		res[2][1] = '8';

		res[3][0] = '8';
		res[4][0] = '4';
		res[5][0] = '7';
//		res[8][0] = '7';*/
		return res;
	}

@Test
public void testIsValidSudoku() throws Exception {
//	char[][] init = init1();
	char[][] init = init2();
	System.out.println(v.isValidSudoku(init));

	/**
	 * ..4...63.
	 * .........
	 * 5......9.
	 *
	 * ...56....
	 * 4.3.....1
	 * ...7.....
	 *
	 * ...5.....
	 * .........
	 * .........
	 *
	 */
}

/** 
* 
* Method: chkRow(char[] arr) 
* 
*/ 
@Test
public void testChkRow() throws Exception {
	char[] c = {'5','3','.','.','7','.','.','.'};
	char[] c1 = {'5','3','.','3','7','.','.','.'};
	System.out.println(v.chkRow(c));
	System.out.println(v.chkRow(c1));
}

/** 
* 
* Method: chkCol(char[][] arr, int col) 
* 
*/ 
@Test
public void testChkCol() throws Exception {
//	char[] c = {'5','3','.','.','7','.','.','.'};
//	char[] c1 = {'5','3','.','3','7','.','.','.'};
//	char[][] init = init1();
//	System.out.println(v.chkCol(init, 0));

	char[][] init = init2();
	System.out.println(v.chkCol(init, 3));
//	System.out.println(v.chkRow(c1));
}

/** 
* 
* Method: chkBox(char[][] arr, int x, int y) 
* 
*/ 
@Test
public void testChkBox() throws Exception {
	char[][] init = init1();
	System.out.println(v.chkBox(init, 0, 0));
} 


} 
