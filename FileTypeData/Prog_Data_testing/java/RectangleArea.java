package com.jason.leetcode;

/**
 * Easy
 * Rectangle Area
 * https://leetcode.com/problems/rectangle-area/
 * Find the total area covered by two rectilinear rectangles in indexA 2D plane.

 Each rectangle is defined by its bottom left corner and top right corner as shown in the figure.

 Rectangle Area
 Assume that the total area is never beyond the maximum possible value of int.
---------------------------------------
 s1:
A,D-----------C,D
| 			  |
| 		E,H--C,H------G,H
|        |    |
A,B-----E,B--C,B
         |
		E,F-----------G,F
---------------------------------------
 s2:

 		A,D				C,D
 				         |
 					     |
 E,H	A,H----(G,H)     |
		 |		 | 		 |
 		A,B-----G,B-----C,B
                 |
                 |
 E,F------------G,F
---------------------------------------
 s3:
 E,H			G,H

 		A,D		G,D		C,D

 E,F	A,F		(G,F)


 		A,B				C,B
---------------------------------------
 s4:
			 E,H			G,H

 A,D		 E,D	C,D

	 		(E,F)	C,F		G,F


 A,B				C,B


 A,D				C,D

		 E,H	G,H
 		(E,F)	G,F

 A,B				C,B
---------------------------------------
AC Runtime: 348 ms
 */
public class RectangleArea {

	public int min(int a,int b){
		return a<=b?a:b;
	}

	public int min(int a,int b,int c){
		int tmp = min(a, b);
		return c<=tmp?c:tmp;
	}

	public int max(int a,int b){
		return a>=b?a:b;
	}

	public int max(int a,int b,int c){
		int tmp = max(a, b);
		return c>=tmp?c:tmp;
	}

	public int computeArea(int A, int B, int C, int D, int E, int F, int G, int H) {
		int w1 = getWH(A,C);
		int h1 = getWH(D,B);
		int w2 = getWH(E,G);
		int h2 = getWH(H,F);
		int a1 = w1*h1;
		int a2 = w2*h2;
		int res = a1+a2;
		//x A C, E G
		//y B D, F H
		int olw = overlap(A,C, E,G);
		int olh = overlap(B,D,F,H);
		if ( olw > 0 && olh > 0)
			res -= olw* olh ;
		return res;
	}


	public int overlap(int l1, int r1, int l2, int r2) {
		if (l2 > r1 || l1 > r2)//not collide
			return -1;
		return min(r1,r2) - max(l1,l2);
	}

	/**
	 * width=(n1=bottomX,n2=topX) A,C E,G
	 * height=(n1=topY,bottonY) D,B H,F
	 * @param n1
	 * @param n2
	 * @return
	 */
	public int getWH(int n1,int n2){
		return Math.abs(n2-n1);
	}




	public int computeArea1(int A, int B, int C, int D, //rect1
						   int E, int F, int G, int H) {//rect2


		int w1 = getWH(A,C);
		int h1 = getWH(D,B);
		int w2 = getWH(E,G);
		int h2 = getWH(H,F);
		int a1 = w1*h1;
		int a2 = w2*h2;
		int res = 0;

		int leftX = min(A,E);
		int rightX = max(C,G);


		int w12 = getWH(A,G);
		if( C<=E || B<=H){//not collide
			res =a1+a2;
		}else{


		}

		/*
		else if( C>E && E>A && D>H && H>B ) {// rec2 E,H in rec1
			int w3 = getWH(E,C);
			int h3 = getWH(B,H);
			int a3 = w3*h3;
			res =a1+a2-a3;
		}else if( A<G && G<C && D>H && H>B ){//rec2 G,H in rec1
			int w3 = getWH(A,G);
			int h3 = getWH(B,H);
			int a3 = w3*h3;
			res =a1+a2-a3;
		}else if( E<A && A<G && F>D && D>H ){//rec2 G,H in rec1
			int w3 = getWH(A,G);
			int h3 = getWH(F,D);
			int a3 = w3*h3;
			res =a1+a2-a3;
		}else if( A<E && E<C && F>D && D>H){
			int w3 = getWH(A,G);
			int h3 = getWH(F,D);
			int a3 = w3*h3;
			res =a1+a2-a3;
		}
		*/
		return res;
	}





}
