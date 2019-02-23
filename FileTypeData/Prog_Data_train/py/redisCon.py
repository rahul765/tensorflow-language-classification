#!/usr/bin/env python
#-*- coding:utf-8 -*-

import redis
#from util.file.csvutil import *


class RedisConn(object):
    conn=None
    def __init__(self, host, port,db ):
        self.host = host
        self.port = port
        self.db = db
        self.conn = redis.Redis(host=self.host,port=self.port,db=self.db)
    def getConn(self):
        # self.pool = redis.ConnectionPool(host=self.host,port=self.port,db=self.db)
        return self.conn
