package com.interview.algorithms.list;

import com.interview.basics.model.collection.list.LinkedList;
import junit.framework.TestCase;
import org.testng.Assert;

/**
 * Created_By: stefanie
 * Date: 14-7-9
 * Time: 下午10:47
 */
public class C3_7_ListRearrangeTest extends TestCase {
    public void testRearrange(){
        LinkedList<String> list = new LinkedList<>();
        String[] nodes = new String[] {"a1","a2","a3","a4","a5","b1","b2","b3","b4","b5"};
        for(String str : nodes) list.add(str);

        list = C3_7_ListRearrange.rearrange(list);

        String[] expected = new String[] {"a1","b1","a2","b2","a3","b3","a4","b4","a5","b5"};
        for(int i = 0; i < list.size(); i ++)
            Assert.assertEquals(list.get(i), expected[i], "The " + i + "th row is wrong");
    }
}
