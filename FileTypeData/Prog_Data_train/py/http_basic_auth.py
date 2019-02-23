#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: http_basic_auth.py
@time: 2017/8/14 上午12:04
"""

from flask import g

from flask_httpauth import HTTPBasicAuth

from project_api.common.exceptions import UNAUTHORIZED


basic_auth = HTTPBasicAuth()


@basic_auth.verify_password
def verify_password(username, password):
    # user = User.query.filter_by(username = username).first()
    # if not user or not user.verify_password(password):
    #     return False
    # g.user = user
    if username != 'username' or password != 'password':
        return False
    g.user_id = 1
    return True


@basic_auth.error_handler
def unauthorized():
    raise UNAUTHORIZED
