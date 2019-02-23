package com.jason.leetcode;

import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import static org.junit.Assert.assertEquals;

/**
 * ZigZagConversion Tester.
 *
 * @author <Authors name>
 * @version 1.0
 */
public class ZigZagConversionTest {

	private ZigZagConversion z = new ZigZagConversion();
	@Before
	public void before() throws Exception {
		z.log = true;
	}

	@After
	public void after() throws Exception {
	}

	/**
	 * Method: convert(String s, int numRows)
	 */
	@Test
	public void testConvert() throws Exception {
		//PAYPALISHIRING
		//PAHN APLSIIG YIR
		/**
		 2:
		 P Y A I H R N
		 A P L S I I G
		 3:
		 P   A   H   N
		 A P L S I I G
		 Y   I   R
		 4:
		 P     I    N
		 A   L S  I G
		 Y A   H R
		 P     I
		 */
		assertEquals("PAYPALISHIRING", z.convert("PAYPALISHIRING", 1));
		assertEquals("PYAIHRNAPLSIIG", z.convert("PAYPALISHIRING", 2));
		assertEquals("PAHNAPLSIIGYIR", z.convert("PAYPALISHIRING", 3));
		assertEquals("PINALSIGYAHRPI", z.convert("PAYPALISHIRING", 4));
	}


} 
