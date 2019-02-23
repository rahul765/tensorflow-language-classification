package com.jason.leetcode;

public class RomantoInteger {

	//I(1)，V(5)，X(10)，L(50)，C(100)，D(500)，M(1000)
	public String[] U = {"O","I","II","III","IV","V","VI","VII","VIII","IX","X"};
	/**
	 * tens
	 * X, 10】 XI, 11 】XII, 12】 XIII, 13】 XIV, 14】 XV, 15 】XVI, 16 】XVII, 17 】XVIII, 18】 XIX, 19】 
     * XX, 20】  XXI, 21 】XXII, 22 】XXIX, 29】 
     * XXX,30】 XXXIV, 34】 XXXV, 35 】XXXIX, 39】 
     * XL,40】
     * L,50 】LI, 51】 LV, 55】 
     * LX, 60 LXV, 65
     * LXX, 70 
     * LXXX, 80
     * XC, 90 】XCIII, 93】 XCV, 95 】XCVIII, 98】 XCIX, 99 】
     * last letter X,L,C
	 */
    public String[] T = {"X","XX","XXX","XL",""};
    /**
     * Hundrunds
     * C, 100 
     * CC, 200 】
     * CCC, 300 】
     * CD, 400】 
     * D, 500 】
     * DC,600 】
     * DCC, 700】 
     * DCCC, 800 】
     * CM, 900】 
     * CM XCIX,999
     * last letter C,D,M
     * Sep:C,DC*,CM
     */
    public String[] H = {"C","CC","CCC","CD",""};
    //public String[] S = {"M"};
    String u = "I";
    String u5 = "V";
    String t = "X";
    String t5 = "L";
    String h = "C";
    String s = "M";
    /**
     * Thousands
     * M, 1000,MC, 1100,MCD, 1400,MD, 1500,MDC, 1600 】MDCLXVI,1666】 MDCCCLXXXVIII, 1888 】MDCCCXCIX, 1899 】MCM, 1900 】MCMLXXVI, 1976】 MCMLXXXIV, 1984】 MCMXC, 1990 】
     * MM,	2000
     * MMM,	3000
     * last letter:M
     * SEP,M*
     */
    
    /**
     * MMM CM XC IX, 3999
     * @param s
     * @return
     */
    public int romanToInt(String s) {
        
        
        
        
        
    	return 0;
    }
	public static void main(String[] args) {
		
	}

}
