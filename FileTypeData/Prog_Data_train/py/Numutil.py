#!/bin/env python
#-*- coding:utf-8 -*-


def getNumsBitcount(num):
    if num==0:
        return 0
    num=abs(num)
    cnt=0
    # print bin(num)
    while num>0:
        num=num>>1
        cnt+=1
    return cnt


res=getNumsBitcount(3)
print 'res',res

