# coding: utf-8
#
# Copyright 2010 Alexandre Fiori
# based on the original Tornado by Facebook
#
# Licensed under the Apache License, Version 2.0 (the "License"); you may
# not use this file except in compliance with the License. You may obtain
# a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
# License for the specific language governing permissions and limitations
# under the License.

import cyclone.web
import functools
import sys

from twisted.python import log
from twisted.internet import reactor

_handlers = []
_BaseHandler = None


class Router:
    def __init__(self):
        self.items = []

    def add(self, method, callback):
        self.items.append((method, callback))

    def __call__(self, *args, **kwargs):
        obj = _BaseHandler(*args, **kwargs)
        for (method, callback) in self.items:
            callback = functools.partial(callback, obj)
            setattr(obj, method.lower(), callback)
        return obj


def route(path=None, method="GET", callback=None, **kwargs):
    if callable(path):
        path, callback = None, path

    def decorator(callback):
        _handlers.append((path, method.lower(), callback, kwargs))
        return callback

    return decorator


def run(**settings):
    global _handlers, _BaseHandler
    port = settings.get("port", 8888)
    interface = settings.get("host", "127.0.0.1")
    log.startLogging(settings.pop("log", sys.stdout))
    _BaseHandler = settings.pop("base_handler", cyclone.web.RequestHandler)

    handlers = {}
    for (path, method, callback, kwargs) in _handlers:
        if path not in handlers:
            handlers[path] = Router()
        handlers[path].add(method, callback)

    _handlers = None

    handlers = handlers.items() + settings.pop("more_handlers", [])
    application = cyclone.web.Application(handlers, **settings)
    reactor.listenTCP(port, application, interface=interface)
    reactor.run()
