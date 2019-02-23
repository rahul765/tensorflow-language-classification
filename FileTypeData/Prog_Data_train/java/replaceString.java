package com.vikas;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

//- WAP to take 3 string - A,B,C. Replace all occurences of B in A by C
public class replaceString {
	
		public static void main(String... args) throws IOException{
			String a,b,c;
			BufferedReader br = new BufferedReader(new InputStreamReader(System.in));
			System.out.println("Enter First String");
	        a = br.readLine();
	        System.out.println("Enter Second String");
	        b = br.readLine();
	        System.out.println("Enter Third String");
	        c = br.readLine();
	        a = a.replaceAll(b, c);
	        System.out.println(a);
		}
}
