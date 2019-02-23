#!/usr/bin/python
# -*- coding: UTF-8 -*-
"""
@desc:  tensor flow加载张量，会话和图
@time:   2017/06/13 05：19
@author: lucy(0_0mirror@sina.com)
@param: 
@output:
"""
import tensorflow as tf
##定义图
g1 = tf.Graph()
with g1.as_default():
    v = tf.get_variable("v", [1], initializer = tf.zeros_initializer) # 设置初始值为0

g2 = tf.Graph()
with g2.as_default():
    v = tf.get_variable("v", [1], initializer = tf.ones_initializer())  # 设置初始值为1
    
with tf.Session(graph = g1) as sess:
    tf.global_variables_initializer().run()
    with tf.variable_scope("", reuse=True):
        print(sess.run(tf.get_variable("v")))

with tf.Session(graph = g2) as sess:
    tf.global_variables_initializer().run()
    with tf.variable_scope("", reuse=True):
        print(sess.run(tf.get_variable("v")))


#测试随机数
