package com.jason.leetcode;

import com.jason.tools.TreeNode;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import static org.junit.Assert.assertEquals;

/**
 * SymmetricTree Tester.
 *
 * @author <Authors name>
 * @version 1.0
 * @since <pre>���� 14, 2015</pre>
 */
public class SymmetricTreeTest {

	private static SymmetricTree s;
	private static TreeNode root;
	public SymmetricTreeTest(){
		s= new SymmetricTree();
		root = TreeNode.initSyTree(2,true);
	}
	@Before
	public void before() throws Exception {

	}

	@After
	public void after() throws Exception {
	}

	/**
	 * Method: isSymmetric(TreeNode root)
	 * [1,2,2,null,3,3]
	 *
	 */
	@Test
	public void testIsSymmetric() throws Exception {
		TreeNode.printTree(this.root);
		s.isSymmetric(this.root);
		assertEquals(true, s.isSymmetric(this.root));
		TreeNode.printTree(this.root);
	}

	/**
	 * Method: traverse(TreeNode tr, List l)
	 */
	@Test
	public void testTraverse() throws Exception {

	}

	/**
	 * Method: invert(TreeNode tr)
	 */
	@Test
	public void testInvert() throws Exception {
	}


} 
