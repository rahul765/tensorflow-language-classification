# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
from StringIO import StringIO
import os
from socket import socket
import struct
import sys
from threading import Thread
from splunk.clilib import cli_common as cli
import logging
from splunk.util import normalizeBoolean


class JavaBridgeError(Exception):
    pass


CACHED_CFG_DIR = os.path.join(os.environ['SPLUNK_HOME'], 'var', 'run', 'splunk', 'dbx')
CACHED_CFG_FILE = os.path.join(CACHED_CFG_DIR, 'bridge.cfg')


class JavaBridge:
    def __init__(self, stdin=None, stdout=sys.stdout, stderr=sys.stderr, env=None):
        (self.stdin, self.stdout, self.stderr) = (stdin, stdout, stderr)
        (self.bridgeHost, self.bridgePort, self.debug) = self.readConfig()
        if self.debug:
            self.initDebugLogger()
        else:
            self.logger = None
        self.returncode = None
        self.env = env

    def initDebugLogger(self):
        self.logger = logging.Logger('spp.dbx.javabridge', level=logging.DEBUG)
        h = logging.FileHandler(
            filename=os.path.join(os.environ['SPLUNK_HOME'], 'var', 'log', 'splunk', 'jbridge_client.log'))
        h.setFormatter(logging.Formatter('%(asctime)s %(levelname)s %(message)s'))
        self.logger.addHandler(h)

    def readConfig(self):
        cfgDir = CACHED_CFG_DIR
        cfgFile = CACHED_CFG_FILE
        if os.path.exists(cfgFile):
            try:
                import pickle

                f = open(cfgFile, 'rb')
                v = pickle.load(f)
                f.close()
                return v
            except:
                pass
        cfg = cli.getConfStanza("java", "bridge")
        val = (cfg.get("addr", "127.0.0.1"), int(cfg.get("port", -1)), normalizeBoolean(cfg.get("debug", "0")))
        try:
            import pickle

            if not os.path.exists(cfgDir):
                os.makedirs(cfgDir)
            f = open(cfgFile, 'wb')
            pickle.dump(val, f)
            f.close()
        except:
            pass
        return val

    def sendEnvironment(self, **kwargs):
        for k, v in kwargs.items():
            self.sendEncoded('E', "%s=%s" % (k, v))

    def sendEncoded(self, type, data):
        enc = unicode(data).encode('utf-8')
        header = struct.pack('>ci', type, len(enc))
        self.socket.send(header)
        self.socket.send(enc)

    def execute(self, cls, *args):
        try:
            if self.logger: self.logger.debug("spp.dbx.javabridge Executing java bridge command %s [%s]" % (cls, args))
            self.socket = socket()
            try:
                self.socket.connect((self.bridgeHost, self.bridgePort))
            except Exception, e:
                raise JavaBridgeError(
                    "Connection to Java bridge server %s:%s failed: %s" % (self.bridgeHost, self.bridgePort, e))
            self.sendEncoded('C', cls)
            if self.env is not None:
                self.sendEnvironment(self.env)
            for arg in args:
                self.sendEncoded('A', arg)
            self.socket.send('.')

            if self.stdin:
                self.pipe(self.stdin)

            self.outputHandler = JavaBridgeOutput(self, self.socket, self.stdout, self.stderr)
            self.outputHandler.run()
            return self.returncode
        except Exception, e:
            import traceback

            if self.logger: self.logger.error(
                "spp.dbx.javabridge Error while executing bridge command: %s\n%s" % (e, traceback.format_exc()))
            return 47

    def onTerminate(self, ret):
        self.returncode = ret
        self.socket.close()

    def getReturncode(self):
        return self.returncode

    def send(self, data):
        self.sendEncoded('I', data)

    def pipe(self, stream, block_size=8192, async=True):
        self.inputHandler = JavaBridgeInput(self, stream, block_size)
        if async:
            self.inputHandler.start()
        else:
            self.inputHandler.run()


class JavaBridgeInput(Thread):
    def __init__(self, bridge, input, block_size=8192):
        super(JavaBridgeInput, self).__init__()
        self.bridge = bridge
        self.input = input
        self.block_size = block_size

    def run(self):
        stream = self.input
        bridge = self.bridge
        while True:
            data = stream.read(self.block_size)
            if not len(data): break
            bridge.send(data)
        bridge.sendEncoded(';', '')


class JavaBridgeOutput(Thread):
    def __init__(self, bridge, socket, stdout=None, stderr=None):
        super(JavaBridgeOutput, self).__init__()
        self.bridge = bridge
        self.socket = socket
        (self.stdout, self.stderr) = (stdout, stderr)

    def run(self):
        while True:
            header = self.socket.recv(int(5))
            if not len(header): break; # reached end of file or end of stream
            type = header[0]
            length = struct.unpack('>i', header[1:])[0]
            target = None
            if type == 'O':
                target = self.stdout
            elif type == 'E':
                target = self.stderr
            elif type == 'T':
                self.bridge.onTerminate(length)
                return
            else:
                raise Exception("Invalid chunk type " + type)
            remaining = length
            while remaining > 0:
                data = self.socket.recv(remaining)
                if not len(data): raise Exception("Premature end of stream: %d remaining" % remaining)
                remaining -= len(data)
                if target: target.write(data)


def executeBridgeCommand(cmd, args=[], checkStatus=False, fetchOutput=False, **kwargs):
    stdout, stderr = (None, None)
    if fetchOutput:
        stdout = StringIO()
        stderr = StringIO()
        kwargs['stdout'] = stdout
        kwargs['stderr'] = stderr
    bc = JavaBridge(**kwargs)
    ret = bc.execute(cmd, *args)
    if checkStatus is True:
        if ret is not 0:
            raise JavaBridgeError("Command %s returned status code %s" % (cmd, ret))
    if fetchOutput:
        return ret, stdout.getvalue(), stderr.getvalue()
    else:
        return ret
