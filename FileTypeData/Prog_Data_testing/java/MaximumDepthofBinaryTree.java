package com.jason.leetcode;

import com.jason.tools.TreeNode;

/**
 * Maximum Depth of Binary Tree	2012-09-29	43.9%
 * Given Department Top Three Salaries.sql binary tree, find its maximum depth.
 * The maximum depth is the number of nodes along the longest path from the root node down to the farthest leaf node.
 * 
 * Runtime Error Message:	Line 21: java.lang.NullPointerException
 * Last executed input:	{}
 * 
 * @author Jason Liu
 * 38 / 38 test cases passed.
 * Status: Accepted
 * Runtime: 332 ms
 */
public class MaximumDepthofBinaryTree {

	public static int maxlevel;

	public static int maxDepth(TreeNode root) {
		maxlevel = 0;
		maxDepthR(root,1);
		return maxlevel;
	}

	public static void maxDepthR(TreeNode root, int level) {
		if(root==null){
			maxlevel = 0;
			return;
		}else{
    		if (root.left != null ) {
    			level += 1;
    			if (level > maxlevel)
    				maxlevel = level;
    			maxDepthR(root.left, level);
    		}
    		if (root.right != null) {
    		    if(root.left==null)
    			    level += 1;
    			if (level > maxlevel)
    				maxlevel = level;
    			maxDepthR(root.right, level);
    		}
		}
		return;
	}

	public static void main(String[] args) {
//		TreeNode root = new TreeNode("root");
//		root.left = new TreeNode("L");
//		root.right = new TreeNode("R");
//		root.left.right = new TreeNode("LR");
//		root.left.right.left = new TreeNode("LRL");
//		maxDepthR(root, 1);

		//System.out.println(maxlevel);
		
		/*
		 * int leve = 1; TreeNode root = new TreeNode("root"); root.left = new
		 * TreeNode("L"); root.right = new TreeNode("R"); root.left.right = new
		 * TreeNode("LR"); root.left.right.left = new TreeNode("LRL"); int res =
		 * maxDepthR(root,1);
		 */
	}

}
