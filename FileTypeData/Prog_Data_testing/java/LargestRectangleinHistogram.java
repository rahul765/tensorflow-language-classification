package com.jason.leetcode;

import java.util.Comparator;
import java.util.PriorityQueue;
import java.util.Queue;

public class LargestRectangleinHistogram {

	public int largestRectangleArea(int[] height) {
        int len = height.length;
        if( len <= 0){
            return 0;
        }
        if( len == 1){
            return height[0];
        }
        Comparator<Integer> OrderIsdn =  new Comparator<Integer>(){  
            public int compare(Integer o1, Integer o2) {
                int numbera = o1.intValue();  
                int numberb = o2.intValue();  
                if(numberb > numbera)  
                {  
                    return -1;  
                }  
                else if(numberb<numbera)  
                {  
                    return 1;
                }  
                else  
                {  
                    return 0;  
                }  
              
            }
		};
		Queue<Integer> q = new PriorityQueue<Integer>(len,OrderIsdn);
		for(int i=0;i<len;i++){
			q.add(i);
		}
		
		
        int min_level=0;
        int tmp_area = 0;
        int max_area = 0;
        
        for(int i=0;i<len;i++){
            if( height[i] > max_area){
                max_area = height[i];
            }
            for(int j=i;j<len;j++){
                
                min_level = height[j];
            }
        }
		return max_area;
	}
	
	
	public static void main(String[] args) {
		
	}

}
