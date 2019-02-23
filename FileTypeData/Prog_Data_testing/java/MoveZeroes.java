package com.jason.leetcode.easy;

/**
 * 283
 * Move Zeroes
 * https://leetcode.com/problems/move-zeroes/
 */
public class MoveZeroes {
    public static boolean log= false;

    public void moveZeroes(int[] nums) {
        if(nums==null||nums.length == 0 || nums.length == 1){
            return;
        }
        int len = nums.length;
        int lastIdx = nums.length -1;
        System.out.println("last index init "+lastIdx);
        for(int i=0;i< len ;i++){
            if(log){
                System.out.println("i "+i);
            }
            if(nums[i]==0){
                int tgt = i;
                System.out.println("tgt "+tgt);
                int j = i;
                while(nums[j]==0 && j<len){j++;}

                lastIdx = lastIdx - (j-i);
                tgt = j;
                if(log) {
                    System.out.println("-----lastidx "+lastIdx+",tgt "+tgt);
                }
                int src = i;
                if(log){
                    System.out.println("i "+i+",src "+src);
                }
                int cnt = 0;
                if(i<=len-1){
                    while(src<=lastIdx){
                        nums[tgt] = nums[src];
                        src++;
                        tgt++;
                        cnt++;
                    }
                }

                i = tgt-cnt;
                if(log) {
                    System.out.println("i "+i);
                }
            }

        }
        for(int i=lastIdx+1;i<len;i++){
            nums[i] = 0;
        }
    }
}
