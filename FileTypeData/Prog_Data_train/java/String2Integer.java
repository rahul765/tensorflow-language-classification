package com.jason.leetcode;

public class String2Integer {

	public int atoi(String str) {
		int res = 0 ;
        if(str==null){
            return 0;
        }
        if(str.length()==0){
        	return 0;
        }
        str.replaceAll(" ",str);
        return res;
    }
	
	public static void main(String[] args) {
		
	}

}
