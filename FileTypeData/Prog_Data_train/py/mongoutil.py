#!/bin/env python
#-*- coding:utf-8 -*-

import pymongo
import util.log.logutil
log = logutil.LogUtil.getStdLog()

conn = pymongo.Connection("127.0.0.1",27017)
db = conn.tage


