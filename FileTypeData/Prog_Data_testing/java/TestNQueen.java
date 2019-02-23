package com.jason.leetcode;

import org.junit.BeforeClass;
import org.junit.Test;
//import org.springframework.context.ApplicationContext;
//import org.springframework.context.support.ClassPathXmlApplicationContext;

import java.util.List;

import static org.junit.Assert.assertEquals;

/**
 * Created by JasonLiu on 2015/8/11.
 */
public class TestNQueen {

//	private static ApplicationContext context;
	private static  NQueens nq;

	@BeforeClass
	public static void  initSpring(){
//		context = new ClassPathXmlApplicationContext("ac.xml");
//		nq = (NQueens) context.getBean("nqueen");
		nq.log = true;
//		nq.log = false;
	}

	@Test
	public void testRes(){
//		List<List<String>> solutions = nq.solveNQueens(6);
		List<List<String>> solutions = nq.solveNQueens1(10);
		System.out.println( solutions.size());
		for(int i=0;i< solutions.size();i++){
			nq.printl( solutions.get(i));
		}
	}


	@Test
	public void testCanSetQ() {
		//		List<String> l = NQueens.getNewBoard(5);
//		NQueens.setNQ(l, 0, 4);
//		NQueens.setNQ(l, 1, 3);
//		NQueens.printl(l);
	}

//
//		10		 ["....Q","..Q..","Q....","...Q.",".Q..."]]


		/*
		.Q..,
		...Q,
		..Q.,
		....,
		*/
//		assertEquals(NQueens.canSetQ(l, 2, 1), -1);
//		assertEquals( 1, NQueens.canSetQ(l, 3, 2));

}
