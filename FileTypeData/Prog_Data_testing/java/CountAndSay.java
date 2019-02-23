package com.jason.leetcode;

public class CountAndSay {

	public static void main(String[] args) {
		
	}
	/**
	 * 1 is read off as "one 1" or 11.
	 * 11 is read off as "two 1s" or 21.
	 * 21 is read off as "one 2, then one 1" or 1211.
	 * 1, 11, 21, 1211, 111221, ...
	 * @param n
	 * @return
	 */
	public String countAndSay(int n) {
		String start ="1";
		String str =start;
		String tmp_str = "";
		for(int i=0;i<n;i++){
			str = getNextString(str);
			//str = 
		}
		
        return "";
    }
	
	public String getNextString(String str){
		String tmp_str = "";
		String res="";
		char c1 = 0;
		char c2 = 0;//start.charAt(0);
		int len = str.length();
		for(int i=0;i<str.length();i+=2){
			c1 = str.charAt(i);
			if(i+1<str.length()){
				c2 = str.charAt(i+1);
			}else{
				res =res+"1"+c2;
				break;
			}
			tmp_str = this.getStr(c1,c2);
			if(i==0){
				res = tmp_str;
			}else{
				res = res+","+tmp_str;
			}
		}
		return res;
	}
	
	public String getStr(char c1,char c2){
		String tmp_str ="";
		if(c2 == ' '){
			tmp_str = "1"+c2;
		}else{
			if(c1==c2 && c1=='1'){
				tmp_str = "21";
			}else if(c1==c2 && c1=='2'){
				tmp_str = "22";
			}else if(c1!=c2 ){
				tmp_str = "1"+c1+"1"+c2;
			}
		}
		return tmp_str;
	}

}
