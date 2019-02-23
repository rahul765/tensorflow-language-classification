# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
import os
import sys
from spp.java.bridge import JavaBridge, JavaBridgeError

stdin = None
if not os.isatty(0):
    stdin = sys.stdin

bridge = JavaBridge(stdin=stdin)
try:
    ret = bridge.execute("com.splunk.bridge.cmd.Reload", *sys.argv[1:])
except JavaBridgeError, e:
    print >> sys.stderr, "Error: %s" % str(e)
    sys.exit(1)

sys.exit(ret)