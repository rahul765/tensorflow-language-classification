#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: get_baidu_requests.py
@time: 2017-12-04 11:36
"""


import time
import requests

# import gevent.monkey
# gevent.monkey.patch_select(aggressive=True)


urls = [
    'https://www.baidu.com'
]*200


def run():
    start = time.time()
    rs = [requests.get(u) for u in urls]
    stop = time.time()
    # print(rs)
    print(stop-start)


if __name__ == '__main__':
    run()
