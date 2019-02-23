package com.jason.leetcode.easy;

import com.jason.tools.TreeNode;

import java.util.LinkedList;
import java.util.List;
import java.util.Stack;

/**
 * 235 easy
 * Lowest Common Ancestor of a Binary Search Tree
 * https://leetcode.com/problems/lowest-common-ancestor-of-a-binary-search-tree/
 * AC: 15 ms
 */
public class LowestCommonAncestorofaBinarySearchTree {

    public static boolean log = false;
    public LinkedList<TreeNode> p1s;
    public LinkedList<TreeNode> p2s;

    public TreeNode lowestCommonAncestor(TreeNode root, TreeNode p, TreeNode q) {
        if(root==null)
            return null;
        if(root.left==null && root.right==null){
            if( p.val ==q.val && p.val == root.val )
                return root;
            return null;
        }
        if(p==null || q==null){
            return null;
        }

        /**
         * one target is root
         */
        if(root.val == p.val || root.val == q.val){
            return root;
        }

        /**
         * p,q is not root
         */
        TreeNode parent = null;
        p1s = null;
        p2s = null;
        LinkedList<TreeNode> pp = new LinkedList<>();
        traverse(root,p,q,pp);

        if(p2s!=null && p1s!=null){
            int minLen = Math.min(p1s.size(),p2s.size());
            int i=0;
            boolean got  =  false;
            if(log){
                System.out.println(p1s);
                System.out.println(p2s);
            }
            for(i=0;i<minLen;i++){
                if(p1s.get(i).val !=p2s.get(i).val ){
                    got = true;
                    break;
                }
            }
            if(got){
                parent = p1s.get(i-1);
            }else{
                //i=0;
                if(i==minLen) {
                    parent = p1s.get(i-1);
                }else{
                    parent = null;
                }
            }
        }
        return parent;
    }

    public void traverse(TreeNode tr ,TreeNode tgt1,TreeNode tgt2, LinkedList<TreeNode> tgtParents){

        tgtParents.add(tr);
        if(tr.val == tgt1.val){
            //get target
            p1s = (LinkedList<TreeNode>) tgtParents.clone();
            if(log) {
                System.out.println("find node 1 " + tr.val);
            }
        }else if(tr.val == tgt2.val) {
            //get target
            p2s = (LinkedList<TreeNode>) tgtParents.clone();
            if (log) {
                System.out.println("find node 2 " + tr.val);
            }
        }

        if(log)
            System.out.println("push "+tr.val);

        if(tr.left!=null){
            traverse(tr.left,tgt1,tgt2,tgtParents);
        }

        if(tr.right!=null){
            traverse(tr.right,tgt1,tgt2,tgtParents);
        }

        if(!tgtParents.isEmpty()) {
            TreeNode pop = tgtParents.remove(tgtParents.size()-1);
            if(log)
                System.out.println("pop "+pop.val);
        }
    }


}
