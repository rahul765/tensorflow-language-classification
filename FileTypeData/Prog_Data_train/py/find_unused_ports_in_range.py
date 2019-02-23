#!/usr/bin/env python

import socket
import sys

argc = len(sys.argv)

def msg_info():
	print 'Usage:\n\t', sys.argv[0], 'lower_port upper_port'

if argc != 3:
	msg_info()
	exit()

lower = int(sys.argv[1])
upper = int(sys.argv[2])

#print 'lower:', lower, 'upper:', upper

i = 0;
port = -1;

for i in range(0, 10):
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	s.bind(('', 0))
	addr = s.getsockname()
	port = addr[1]
	s.close()
	if port >= lower and port <= upper:
		print port
		break
