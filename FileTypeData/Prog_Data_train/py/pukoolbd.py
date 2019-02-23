# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
import sys
from spp.java.bridge import JavaBridge, JavaBridgeError

cmd = JavaBridge(stdin=sys.stdin, stdout=sys.stdout, stderr=None)
try:
    sys.exit(cmd.execute("com.splunk.dbx.lookup.DatabaseLookupExecutor", *sys.argv[1:]))
except JavaBridgeError, e:
    print >> sys.stderr, "Error: %s" % str(e)
    sys.exit(1)