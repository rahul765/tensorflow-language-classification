package com.interview.algorithms.design;

import junit.framework.TestCase;

import java.util.HashMap;
import java.util.List;
import java.util.Map;


public class C10_6_SampleForInfiniteSetTest extends TestCase {

    public void testSimpling() throws Exception {
        Map<String, Integer> map = new HashMap<String, Integer>();
        for(int i = 0; i < 100000; i++){
            List<String> set = C10_6_SampleForInfiniteSet.simpling("test_documents/C10_6_SampleForInfiniteSetText1.txt", 10);
            for(String query : set){
                if(map.containsKey(query)) map.put(query, map.get(query).intValue()+1);
                else map.put(query, 1);
            }
        }

        int average = (1000000/747);

        for(Map.Entry<String, Integer> entry : map.entrySet()){
            int count = entry.getValue().intValue();
            System.out.println(entry.getKey() + ":" + entry.getValue());
            assertTrue(count > average*0.9 && count < average*1.1);   //1338 +- 100

        }
    }
}
