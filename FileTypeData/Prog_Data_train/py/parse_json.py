#!/usr/bin/env python

import sys
import math
import json
from pprint import pprint

print 'Number of arguments:', len(sys.argv), 'arguments.'
print 'Argument List:', str(sys.argv)

json_data=open('public.json')

data = json.load(json_data)
pprint(data)

n = len(data)

capital = range(0, n)
total = range(0, n)

for i in range(0, n):
	capital[i] = float(data[i]["capital"])
	total[i] = float(data[i]["total"])

for i in range(0, n):
	print '{0:s} - {1:8.2f} - {2:8.2f} - ({3:s})'.format(data[i]["timestamp"], capital[i], total[i], data[i]["info"])

for i in range(0, n):
	sys.stdout.write("{0} # {1:>8} # {2:>8} # ({3})\n".format(data[i]["timestamp"], data[i]["capital"], data[i]["total"], data[i]["info"]))

json_data.close()

print 'The value of PI is approximately {0:.4f}.'.format(math.pi)
