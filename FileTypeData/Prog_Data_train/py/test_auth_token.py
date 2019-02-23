#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: test_auth_token.py
@time: 2017/8/15 下午1:20
"""


from project_api.common.auth_token import generate_auth_token, verify_auth_token


def test_token():
    """
    eyJhbGciOiJIUzI1NiIsImV4cCI6MTUwMjc3NTE2MiwiaWF0IjoxNTAyNzc0NTYyfQ.eyJ1c2VyX2lkIjoxMjN9.wBBWLueugtKCYL27YYIXxVpsCbMJuigg8Iy8P6HGTH4
    {u'user_id': 123}
    :return:
    """
    test_user_id = 123
    s = generate_auth_token(test_user_id)
    print s
    o = verify_auth_token(s)
    print o
    assert o.get('user_id') == test_user_id


def test_get_token():
    test_user_id = 123
    s = generate_auth_token(test_user_id)
    print s


def test_verify_token():
    test_token_str = 'eyJhbGciOiJIUzI1NiIsImV4cCI6MTUwMjc3NTg4OSwiaWF0IjoxNTAyNzc1Mjg5fQ.eyJ1c2VyX2lkIjoxfQ.Z4G_vjLzkV_alxXBPfxa0H4Kh5gC4PD5LA-iqNynB7o'
    verify_auth_token(test_token_str)


if __name__ == '__main__':
    # test_token()
    # test_get_token()
    test_verify_token()
