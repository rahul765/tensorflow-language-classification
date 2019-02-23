package com.jason.leetcode;

import com.jason.tools.TreeNode;

import java.util.LinkedList;
import java.util.List;

/**
 * Easy
 * Symmetric Tree
 * https://leetcode.com/problems/symmetric-tree/
 * Given indexA binary tree, check whether it is indexA mirror of itself (ie, symmetric around its center).

 For example, this binary tree is symmetric:

 1
 / \
 2   2
 / \ / \
 3  4 4  3
 But the following is not:
 1
 / \
 2   2
 \   \
 3    3
 * AC Runtime: 356 ms
 */
public class SymmetricTree {

	private static boolean isSy = true;

	public boolean isSymmetric(TreeNode root) {
		return (root == null)?true:judge(root.left, root.right);
	}

	boolean judge(TreeNode node1, TreeNode node2){
		if(node1 == null && node2 == null)
			return true;
		if(node1 == null || node2 == null || node1.val != node2.val)
			return false;
		return judge(node1.left, node2.right) && judge(node1.right, node2.left);
	}


	public boolean isSymmetric1(TreeNode root) {
		isSy = true;
		if(root == null ){
			return true;
		}
		Boolean res = true;
		List<TreeNode> tr1 = new LinkedList<>();
		traversem(root, tr1);
		invert(root);
//		System.out.println(res);
//		if(isSy){
		List<TreeNode> tr2 = new LinkedList<>();
		traversem(root,tr2);
		for(int i=0;i<tr2.size();i++){
			if( tr1.get(i).val != tr2.get(i).val){
				res = false;
			}
		}
		tr2.clear();
//		}else{
//			res = false;
//		}
		return res;
	}

	public static void traversem(TreeNode tr,List l){
		if(tr.left!=null){
			traversem(tr.left, l);
		}
		l.add(tr);
		if(tr.right!=null){
			traversem(tr.right, l);
		}
	}



	public static void invert(TreeNode tr){
		if(tr.left==null && tr.right==null){
			return;
		}
//		if((tr.left==null && tr.right!=null )||(tr.right==null && tr.left!=null)){
//			isSy=false;
//			return;
//		}
		TreeNode tmp = tr.left;
		tr.left = tr.right;
		tr.right = tmp;
		if(tr.left!=null){
			invert( tr.left );
		}
		if(tr.right!=null){
			invert( tr.right );
		}
	}

	/*
	public void traverse(TreeNode tr){
		if(tr.left!=null && tr.right!=null) {
			if (tr.left.val != tr.right.val) {
				isSy = false;
				return;
			}
			traverse(tr.left);
			traverse(tr.right);
		}else if( tr.left!=tr.right && (tr.left==null || tr.right==null) ){
			isSy = false;
			return;
		}
		return;
	}*/



}
