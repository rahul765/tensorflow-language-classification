#!/usr/bin/env python
# encoding: utf-8

"""
@author: zhanghe
@software: PyCharm
@file: run_project_api.py
@time: 2017/8/12 下午1:05
"""

from project_api.app import app

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True)
