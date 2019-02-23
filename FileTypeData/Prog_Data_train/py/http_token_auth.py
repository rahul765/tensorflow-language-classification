#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: http_token_auth.py
@time: 2017/8/15 上午12:05
"""


from flask_httpauth import HTTPTokenAuth


from project_api.common.auth_token import verify_auth_token


token_auth = HTTPTokenAuth(scheme='Token')


@token_auth.verify_token
def verify_token(token):
    return verify_auth_token(token)
