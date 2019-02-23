# -*- coding: utf-8 -*-
"""
Created on Sat Mar  8 23:02:43 2014

@author: atproofer
"""

#Implementation of Quadtrees
class Quadtree:
     def __init__(self):
         self.MAX_OBJECTS = 10;
         self.MAX_LEVELS = 5;
 
  private int level;
  private List objects;
  private Rectangle bounds;
  private Quadtree[] nodes;
 
 /*
  * Constructor
  */
  public Quadtree(int pLevel, Rectangle pBounds) {
   level = pLevel;
   objects = new ArrayList();
   bounds = pBounds;
   nodes = new Quadtree[4];
  }
}