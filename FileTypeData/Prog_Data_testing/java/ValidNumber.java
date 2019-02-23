package com.jason.leetcode;

import java.util.regex.Pattern;

public class ValidNumber {
	
	public boolean isNumber(String s) {
		String str = s.trim();
		boolean res = false;
		Pattern singNum = Pattern.compile("[0-9]");
		Pattern intNum = Pattern.compile("[-+]{0,1}[0-9]+");
		Pattern floNum = Pattern.compile("[-+]{0,1}\\d*\\.\\d+|[-+]{0,1}\\d+\\.\\d*");
		//Pattern floatOrIntNum = Pattern.compile("\\d+\\.\\d+");
		
		if(str.length()<=0){
			res = false;
		}else if(str.length()==1){
			res = singNum.matcher(str).matches();
		}else{
			int fidx = 0;
			int idx=-1;
			int i=0;
			//idx = str.indexOf('e',2);
			//System.out.println("e idx:"+idx);
			while( (idx = str.indexOf('e',fidx)) >0){
				//System.out.println("e idx:"+idx);
				fidx = idx+1;
				i++;
			}
			
			//System.out.println("i:"+i+",idx:"+fidx);
			if(i>1){
				res = false;
			}else if(i==0){//i==0 无e 
				if(floNum.matcher(str).matches()||intNum.matcher(str).matches()  ){
					res = true;
				}
			}else if(i==1){//i==1 e
				idx = fidx-1;
				if(idx>0){//got e
					String numb = str.substring(0,idx);
					String numa = str.substring(idx+1);
					//System.out.println(numb+","+numa);
					if( (floNum.matcher(numb).matches()||intNum.matcher(numb).matches()) && intNum.matcher(numa).matches()  ){
						res =true;
					}
				}else{
					res = false;
				}
			}
		}
		return res;
    }

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		ValidNumber v = new ValidNumber();
		String[] s={"0"," 0.1 ","abc","1 Department Top Three Salaries.sql","e10"};
		for(int i=0;i<s.length;i++){
			System.out.println( s[i] + v.isNumber(s[i]) );
		}
		
		
		
	}

}
