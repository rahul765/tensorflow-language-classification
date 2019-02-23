package com.jason.leetcode;

import com.jason.tools.CollectionTool;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;

import java.util.*;

import static org.junit.Assert.assertEquals;

/**
 * LengthofLastWord Tester.
 *
 * @author <Authors name>
 * @version 1.0
 * @since <pre>���� 13, 2015</pre>
 */
@RunWith(Parameterized.class)
public class LengthofLastWordTest {

	private static LengthofLastWord ll = new LengthofLastWord();
	private String param;
	private int expect;

	@Parameterized.Parameters
	public static Collection data() {
		return Arrays.asList(
				new Object[][]{
						{"Hello World",5},
						{"Hello Wor",3},
						{" ",0},
						{" aaa indexA   ",1},
				});
	}

	public LengthofLastWordTest(String param,int expect){
		this.param = param;
		this.expect = expect;
	}

	@Before
	public void before() throws Exception {
		ll.log = true;
	}

	@After
	public void after() throws Exception {
	}

	/**
	 * Method: lengthOfLastWord(String s)
	 */
	@Test
	public void testLengthOfLastWord() throws Exception {

		List l = Arrays.asList(
				new Object[][][]{
						{{0, 1, 2, 4, 5, 7}, {"0->2", "4->5", "7"}},
						{{0, 1, 2, 4, 5, 7}, {"0->2", "4->5", "8"}}
				});

		List res = new ArrayList();
		System.out.println(l.size());
		for(int i=0;i<l.size();i++)	{
			Object[] o = (Object[])l.get(i);
			res.add((Object[])o[0]);
			res.add((Object[])o[1]);
			CollectionTool.printArray((Object[]) o[0]);
			CollectionTool.printArray((Object[])o[1]);
		}


//		assertEquals(expect,ll.lengthOfLastWord(param));
	}



} 
