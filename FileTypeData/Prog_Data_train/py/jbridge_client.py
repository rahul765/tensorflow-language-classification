# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
import os
import sys
from spp.java.bridge import JavaBridge, JavaBridgeError

if len(sys.argv) < 2:
    print "Usage: %s <class> [arguments...]" % sys.argv[0]
    sys.exit(1)

stdin = None
if not os.isatty(0):
    stdin = sys.stdin

bridge = JavaBridge(stdin=stdin)
try:
    ret = bridge.execute(sys.argv[1], *sys.argv[2:])
except JavaBridgeError, e:
    print >> sys.stderr, "Error: %s" % str(e)
    sys.exit(1)

sys.exit(ret)