package com.interview.algorithms.list;

import com.interview.basics.model.collection.list.LinkedList;
import com.interview.basics.model.collection.list.Node;
import com.interview.util.TestUtil;
import junit.framework.TestCase;

/**
 * Created_By: stefanie
 * Date: 14-7-30
 * Time: 下午11:08
 */
public class C3_16_FirstCommonNodeTest extends TestCase {

    public void testFind() throws Exception {
        LinkedList<Integer> list = TestUtil.generateLinkedList(5);
        LinkedList<Integer> list1 = TestUtil.generateLinkedList(5);
        LinkedList<Integer> list2 = TestUtil.generateLinkedList(3);
        list1.getTail().next = list.getHead();
        list1.resize();
        list2.getTail().next = list.getHead();
        list2.resize();

        Node node = C3_16_FirstCommonNode.find(list1, list2);
        assertEquals(list.getHead(), node);

        node = C3_16_FirstCommonNode.find(list2, list1);
        assertEquals(list.getHead(), node);
    }
}
