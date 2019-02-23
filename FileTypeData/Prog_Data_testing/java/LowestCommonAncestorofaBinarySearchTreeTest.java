package com.jason.leetcode; 

import com.jason.leetcode.easy.LowestCommonAncestorofaBinarySearchTree;
import com.jason.tools.TreeNode;
import org.junit.Test;
import org.junit.Before; 
import org.junit.After;

/** 
* LowestCommonAncestorofaBinarySearchTree Tester. 
* 
* @author <Authors name> 
* @since <pre>九月 29, 2015</pre> 
* @version 1.0 
*/ 
public class LowestCommonAncestorofaBinarySearchTreeTest { 

    LowestCommonAncestorofaBinarySearchTree l;
    @Before
    public void before() throws Exception {
        l = new LowestCommonAncestorofaBinarySearchTree();

    } 

    @After
    public void after() throws Exception {
        
    }

   /** 
    * 
    * Method: lowestCommonAncestor(TreeNode root, TreeNode p, TreeNode q) 
    * 
    */ 
    @Test
    public void testLowestCommonAncestor() throws Exception {
        TreeNode t = TreeNode.initNormalTree(1);
        TreeNode t1 = new TreeNode(4);
        TreeNode t2 = new TreeNode(5);
//        TreeNode t2 = new TreeNode(6);
//        LinkedList<TreeNode> s = new LinkedList<>();
//        l.traverse(t, t1,t2,s);
//        CollectionTool.printList((List) l.p1s);
//        CollectionTool.printList((List) l.p2s);

         TreeNode parent = l.lowestCommonAncestor(t, t1, t2);
        System.out.println(parent);

    }

    @Test
    public void test2(){
        TreeNode t = TreeNode.initNormalTree(2);
        TreeNode t1 = new TreeNode(1);
        TreeNode t2 = new TreeNode(2);

        TreeNode parent = l.lowestCommonAncestor(t, t1, t2);
        System.out.println(parent);
    }

    @Test
    public void test3(){
        l.log = true;
        TreeNode t = TreeNode.initNormalTree(3);
        TreeNode t1 = new TreeNode(1);
        TreeNode t2 = new TreeNode(3);

        TreeNode parent = l.lowestCommonAncestor(t, t1, t2);
        System.out.println(parent);
    }

    @Test
    public void test4(){
        l.log = true;
        TreeNode t = TreeNode.initNormalTree(4);
        TreeNode t1 = new TreeNode(3);
        TreeNode t2 = new TreeNode(1);

        TreeNode parent = l.lowestCommonAncestor(t, t1, t2);
        System.out.println(parent);
    }



   /** 
    * 
    * Method: traverse(TreeNode tr, TreeNode target, Stack<TreeNode> tgtParents) 
    * 
    */ 
    @Test
    public void testTraverse() throws Exception {
         
    }

}
