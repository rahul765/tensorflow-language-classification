package com.jason.leetcode;

import java.util.HashMap;

public class LRUCacheDL {
    //Node to store the key-value pairs.
     class Node{
        int key;
        int value;
        Node next;
        Node prev;
        public Node(int x , int y, Node n , Node p){
            key=x;
            value=y;
            next=n;
            prev=p;
        }
     }

    // Doubly link list of type Node.    
    class DoublyLL{
        Node first;
        Node last;
        int size;
        int count;
        public DoublyLL(int c){
            size=c;
            count=0;
            first=null;
            last=null;
        }
    }
    
    HashMap<Integer,Node> map;
    DoublyLL dll;
    public LRUCacheDL(int capacity) {
        dll=new DoublyLL(capacity);
        map=new HashMap <Integer,Node> ();
    }

    public int get(int key) {
        if(map.containsKey(key)){
            Node n=map.get(key);
            if(n.prev!=null){
                if(dll.last==n)
                    dll.last=n.prev;
                n.prev.next=n.next;
                if(n.next!=null)
                    n.next.prev=n.prev;
                n.prev=null;
                n.next=dll.first;
                dll.first.prev=n;
                dll.first=n;
            }
            return n.value;
        }else{
            return -1;
        }
    }

    public void set(int key, int value) {
         Node n;

         if(!map.containsKey(key)){
            n=new Node(key,value,null,null);
            if(dll.size!=dll.count){
                    n.next=dll.first;
                    if(dll.count!=0){
                        dll.first.prev=n;
                    }else{
                        dll.last=n;
                    }
                    dll.first=n;
                    dll.count++; 
            }else{
                if(dll.count!=1){
                    map.remove(dll.last.key);
                    dll.last.prev.next=null;
                    dll.last=dll.last.prev;
                    dll.first.prev=n;
                    n.next=dll.first;
                    dll.first=n;

                }else{   
                    map.remove(dll.first.key);
                    dll.first=n;
                    dll.last=n;
                }

            }
            map.put(key,n);
        }else{
            n=map.get(key);
            n.value=value;
            if(n.prev!=null){
                if(dll.last==n)
                    dll.last=n.prev;
                n.prev.next=n.next;
                if(n.next!=null)
                    n.next.prev=n.prev;
                n.prev=null;
                n.next=dll.first;
    
                dll.first.prev=n;
                dll.first=n;
            }
        }
    }
}