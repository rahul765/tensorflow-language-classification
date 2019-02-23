#!/usr/bin/env python
# -*- coding: utf-8 -*-
from bs4 import BeautifulSoup
import urllib2

response = urllib2.urlopen('http://data.nanhai.gov.cn/cms/sites/sjzy/load_sj_theme.jsp?tid=all&page=1')
html = response.read()
print html

