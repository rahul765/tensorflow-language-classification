package com.jason.leetcode;

import java.util.HashSet;
import java.util.Set;

/**
 * Easy
 * Happy Number
 Write an algorithm to determine if a number is "happy".
 A happy number is a number defined by the following process: Starting with any positive integer,
 replace the number by the sum of the squares of its digits, and repeat the process until the number equals 1 (where it will stay),
 or it loops endlessly in a cycle which does not include 1. Those numbers for which this process ends in 1 are happy numbers.
 Example: 19 is a happy number
 12 + 92 = 82
 82 + 22 = 68
 62 + 82 = 100
 12 + 02 + 02 = 1

 */
public class HappyNumber {


    /**
     * hashtable,math
     * 0,0
     * 1,1 Y
     * 2,4->16->37->58->89->145->42->20->4
     * 3,9->81->65->36+25=61->37->9+49=58->25+64=89->...4
     * 4->....4
     * 5->25->4+25=29->4+81=85->64+25=89->64+81=145->...4
     * 6->36->9+36=45->16+25=41->17->50->...4
     * 7->49->16+81=97->81+49=130->1+9=10>1  Y
     * 8->64->36+16=52->25+4=29->4+81=85->64+25=89->64+81=145->...4
     * 9->...4
     * @param n
     * @return
     */
    public boolean isHappy(int n) {
//        if(n==0)
        initSet();
        if(endnumber.contains(n)){
            return true;
        }

        return false;
    }

    private static final Set<Integer> endnumber = new HashSet<>();
    public static void initSet(){
        endnumber.add(1);
        endnumber.add(7);
    }

    public long square(int n){
        if(n==0)
            return 0;
        return n*n;
    }

}
