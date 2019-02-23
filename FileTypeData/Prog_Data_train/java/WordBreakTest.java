package com.interview.leetcode.dp;

import junit.framework.TestCase;

import java.util.HashSet;
import java.util.List;
import java.util.Set;

/**
 * Created_By: stefanie
 * Date: 14-11-16
 * Time: 下午5:55
 *
 *
 */
public class WordBreakTest extends TestCase {
    static Set<String> dict = new HashSet<>();
    static{
        dict.add("leet");
        dict.add("lee");
        dict.add("tco");
        dict.add("de");
        dict.add("code");
        dict.add("a");
        dict.add("b");
    }

    public void testCouldBreak() throws Exception {
        assertTrue(OneSequenceDP.WordBreak.couldBreak("ab", dict));
    }

    public void testWordBreak(){
        List<String> sols = MemoDP.WordBreak.wordBreak("leetcode", dict);
        for(String sol : sols) System.out.println(sol);
    }
}
