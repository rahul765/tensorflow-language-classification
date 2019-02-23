#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: get_baidu_grequests.py
@time: 2017-12-04 11:36
"""


import time
import grequests

# import gevent.monkey
# gevent.monkey.patch_select(aggressive=True)


urls = [
    'https://www.baidu.com'
]*200

rs = (grequests.get(u) for u in urls)


def run():
    start = time.time()
    res = grequests.map(rs)
    stop = time.time()
    # print(res)
    print(stop-start)


if __name__ == '__main__':
    run()
