package com.jason.leetcode;

import com.jason.tools.CollectionTool;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;

import java.util.Arrays;
import java.util.Collection;
import java.util.LinkedList;
import java.util.List;

import static org.junit.Assert.assertEquals;

@RunWith(Parameterized.class)
public class SummaryRangesTest {

	public static SummaryRanges s = new SummaryRanges();
	public List<String> expect;
	public int[] parm;

	public SummaryRangesTest(Object[]parm,Object[] expect){
		this.parm = new int[parm.length];
		for(int i=0;i<parm.length;i++){
			this.parm[i] = (int) parm[i];
		}
		this.expect = new LinkedList<>();
		for(int i=0;i<expect.length;i++){
			this.expect.add((String)expect[i]);
		}
	}

	@Parameterized.Parameters
	public static Collection data() {
		return Arrays.asList(
				new Object[][][]{
						// 0 1 2 3 4 5 6 7
						{ {0,1},		{"0->1"} },
						{ {0,1,2},		{"0->2"} },
						{ {0,1,9},		{"0->1","9"} },
						{ {-2147483648,-2147483647,2147483647},	{"-2147483648->-2147483647","2147483647"} },
						{ {0,5,9},		{"0","5","9"}},
						{ {0,3,5,7,9},		{"0","3","5","7","9"}},
						{ {0,1,2,4},		{"0->2","4"} },
						{ {0,1,2,4},		{"0->2","4"} },
						{ {0,1,2,4,5,7},		{"0->2","4->5","7"} },
						{ {0,1,2,4,5,7,9},		{"0->2","4->5","7","9"} },
						{ {0,1,2,3,5,7,9},		{"0->3","5","7","9"} },
						{ {0,1,2,3,5,7,9,11},	{"0->3","5","7","9","11"} },
						{ {0,2,3,5,7,9,11},		{"0","2->3","5","7","9","11"} },
						{ {0,2,3,4,5,9,11},		{"0","2->5","9","11"} },
				});

	}

	@Before
	public void before() throws Exception {
		s.log = true;
	}

	@After
	public void after() throws Exception {
	}

	@Test
	public void testSummaryRanges() throws Exception {
		Thread.sleep(500);
		CollectionTool.printArray(this.parm);
		List<String> res = s.summaryRanges(this.parm);
		assertEquals(this.expect,res);
	}


} 
