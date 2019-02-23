#!/usr/bin/env python

import sys
from subprocess import call

argc = len(sys.argv)

def msg_info():
	print 'Usage:\n\t', sys.argv[0], 'push/pull IP SRC DST'

if argc != 5:
	msg_info()
	exit()

op = sys.argv[1]
ip = sys.argv[2]
src = sys.argv[3]
dst = sys.argv[4]

ip_port = ip + ':5555'

call(['adb', 'disconnect', ip])
call(['adb', 'connect', ip])
call(['adb', '-s', ip_port, 'root'])
call(['adb', '-s', ip_port, 'shell', 'mount', '-o', 'remount,rw', '/system'])
call(['adb', '-s', ip_port, op, src, dst])
