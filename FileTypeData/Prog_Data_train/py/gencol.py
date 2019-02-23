#!/usr/bin/env  python
#-*- coding:utf-8 -*-

pykws = ['for', 'try', 'def', 'and', 'del', 'not']

def main():
    ts = '['
    ss = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f']
    for ii in ss:
        for jj in ss:
            for kk in ss:
                item = '%s%s%s' %(ii, jj, kk)
                tmp = ''
                if ii in ['0','1','2','3','4','5','6','7','8','9']:
                    tmp = '"s%s", ' %(item)
                elif item in pykws:
                    tmp = '"s%s", ' %(item)
                else:
                    tmp = '"%s", ' %(item)
                ts = ts + tmp
    print ts

if "__main__" == __name__:
    main()
