# Copyright (C) 2015 Gerrit Addiks <gerrit@addiks.net>
# https://github.com/addiks/gedit-dbgp-plugin
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

from gi.repository import GLib, Gtk, GObject, Gedit, Gio, Notify
from profilemanager import WithProfileManagerMixin
from session import DebugSession
from gladehandler import GladeHandler
import os
from time import sleep
import socket
from os.path import expanduser
from _thread import start_new_thread
import xml.etree.ElementTree as ElementTree

from helpers import (
    file_get_contents,
    file_put_contents,
)


class AddiksDBGPApp(GObject.Object, Gedit.AppActivatable, WithProfileManagerMixin):
    app = GObject.property(type=Gedit.App)

    def __init__(self):
        GObject.Object.__init__(self)
        Notify.init("gedit_addiks_xdebug")
        self._listening_sockets = []
        self._active_sessions = []
        self._breakpoints = None
        self._glade_builder = None
        self._glade_handler = None

    def do_activate(self):
        AddiksDBGPApp.__instance = self
        self.app.add_accelerator("<Primary><Shift>D", "win.switch_listener", None)
        self.menu_ext = self.extend_menu("tools-section")
        item = Gio.MenuItem.new(_("Switch listener"), "win.switch_listener")
        self.menu_ext.append_menu_item(item)

    def do_deactivate(self):
        AddiksDBGPApp.__instance = None
        self.menu_ext = None
        self.app.remove_accelerator("win.start_listener", None)

    def do_update_state(self):
        pass

    def open_window_file(self, filePath, line=0, column=0):

        found = False
        tab = None
        window = None

        for view in self.get_all_views():
            document = view.view.get_buffer()
            if document is not None and document.get_location() is not None:
                viewFilePath = document.get_location().get_path()
                if filePath == viewFilePath:
                    found = True
                    window = self.get_window_by_view(view.view).window
                    tab = window.get_tab_from_location(document.get_location())
                    window.set_active_tab(tab)

        if not found:
            location = Gio.File.new_for_path(filePath)
            for window in self.get_all_windows():
                window = window.window
                tab = window.create_tab_from_location(location, None, line, column, False, True)
                found = True
                break

            if not found:
                window = self.app.create_window()
                tab = window.create_tab_from_location(location, None, line, column, False, True)

        # move to line
        view = tab.get_view()
        document = view.get_buffer()
        textIter = document.get_iter_at_line(line-1)
        document.place_cursor(textIter)
        view.scroll_to_iter(textIter, 0.3, False, 0.0, 0.5)
        start_new_thread(self.delayed_present, (window, ))
        return tab

    def delayed_present(self, window):
        sleep(0.01)
        GLib.idle_add(window.present)

    def __show_dialog(self, message):
        GLib.idle_add(self.__do_show_dialog, message)

    def __do_show_dialog(self, message):

        notification = Notify.Notification.new("Gedit - XDebug client", message)
        success = notification.show()

        if not success:
            dialog = Gtk.MessageDialog(
                None,
                None,
                Gtk.MessageType.ERROR,
                Gtk.ButtonsType.CLOSE,
                message)
            dialog.connect("response", lambda a, b, c=None: dialog.destroy())
            dialog.run()

    # ## SINGLETON

    __instance = None

    @staticmethod
    def get():
        if AddiksDBGPApp.__instance is None:
            AddiksDBGPApp.__instance = AddiksDBGPApp()
        return AddiksDBGPApp.__instance

    # ## WINDOW / VIEW MANAGEMENT

    windows = []

    def get_all_windows(self):
        return self.windows

    def register_window(self, window):
        if window not in self.windows:
            self.windows.append(window)

    def unregister_window(self, window):
        if window in self.windows:
            self.windows.remove(window)

    def get_window_by_view(self, view):
        for window in self.windows:
            if view in window.window.get_views():
                return window

    views = []

    def get_all_views(self):
        return self.views

    def register_view(self, view):
        if view not in self.views:
            self.views.append(view)

    def unregister_view(self, view):
        if view in self.views:
            self.views.remove(view)

    # ## DBGP PORT LISTEN

    def _connectDbgp(self, host, port, ideKey):
        self._dbgpSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self._dbgpSocket.connect((host, port))

    def _disconnectDbgp(self, host, port, ideKey):
        if self._dbgpSocket is not None:
            pass

    # ## SOCKETS

    def start_listening(self, action, data=None):
        ports = []
        dbgpProxies = []
        for profileName in self.get_profiles():
            profile = self.get_profile(profileName)
            ports.append(profile['port'])
            if profile.get('dbgp_active'):
                dbgpProxies.append([
                    profile['dbgp_host'],
                    profile['dbgp_port'],
                    profile['dbgp_ide_key'],
                ])
        openedSockets = []
        port = None
        try:
            for port in set(ports):
                listenSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                self._listening_sockets.append(listenSocket)
                listenSocket.bind(("0.0.0.0", int(port)))
                openedSockets.append(listenSocket)
                start_new_thread(self._listenPort, (listenSocket, ))

            for dbgpHost, dbgpPort, ideKey in dbgpProxies:
                start_new_thread(self.dbgp_proxy_start, (dbgpHost, dbgpPort, ideKey, ports[0], ))

            for view in self.get_all_views():
                view.show_breakpoint_gutter()

            for window in self.get_all_windows():
                window.set_listen_menu_set_started()

        except OSError:
            for listenSocket in openedSockets:
                listenSocket.close()
            self.__show_dialog("Cannot open port, the port "+str(int(port))+" is already in use")

    def does_listen(self):
        return bool(self._listening_sockets)

    def _listenPort(self, listenSocket):
        listenSocket.listen(5)
        listenSocket.settimeout(0.5)
        while listenSocket in self._listening_sockets:
            try:
                (clientSocket, address) = listenSocket.accept()
                start_new_thread(self._acceptClient, (clientSocket, address, ))
            except (socket.timeout, OSError):
                pass

    def _acceptClient(self, clientSocket, address=None):
        session = DebugSession(self, clientSocket)
        self._active_sessions.append(session)
        session.init()

    def stop_listening(self,action, data=None):
        for socket_ in self._listening_sockets:
            socket_.close()
        self._listening_sockets = []

        for view in self.get_all_views():
            view.hide_breakpoint_gutter()

        for window in self.get_all_windows():
            window.set_listen_menu_set_stopped()

    def switch_listening(self, action, data=None):
        if self.does_listen():
            self.stop_listening(action, data)
        else:
            self.start_listening(action, data)

    def dbgp_proxy_start(self, hostname, port, ideKey, listenPort=9001):
        dbgpSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        dbgpSocket.settimeout(0.5)
        address = (str(hostname), int(port))
        try:
            dbgpSocket.connect(address)
            dbgpSocket.send(bytes("proxyinit -p "+str(int(listenPort))+" -k "+ideKey+" -m 0\0", 'UTF-8'))
            responseXmlData = dbgpSocket.recv(1024).decode("utf-8")
            responseXml = ElementTree.fromstring(responseXmlData)
            if responseXml.attrib['success'] != "1":
                errorMessage = responseXml[0][0].text
                self.__show_dialog("Error registering with dbgp-Proxy "+repr(address)+": "+errorMessage)
        except ConnectionRefusedError:
            self.__show_dialog("Error connecting to dbgp-Proxy "+repr(address)+": Connection refused!")
        except (TimeoutError, socket.timeout):
            self.__show_dialog("Error connecting to dbgp-Proxy "+repr(address)+": Timeout!")

    def dbgp_proxy_stop(self, hostname, port, ideKey):
        dbgpSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        address = (hostname, int(port))
        try:
            dbgpSocket.connect(address)
            dbgpSocket.send(bytes("proxystop -k "+ideKey+"\0", 'UTF-8'))
            responseXmlData = dbgpSocket.recv(1024).decode("utf-8")
            responseXml = ElementTree.fromstring(responseXmlData)
            if responseXml.attrib['success'] != "1":
                errorMessage = responseXml[0][0].text
                self.__show_dialog("Error registering with dbgp-Proxy "+repr(address)+": "+errorMessage)
        except ConnectionRefusedError:
            self.__show_dialog("Error connecting to dbgp-Proxy "+repr(address)+": Connection refused!")

    def get_active_sessions(self):
        return self._active_sessions

    def remove_session(self, session):
        if session in self._active_sessions:
            self._active_sessions.remove(session)

    def _runBrowser(self, url, ideKey):

        if '?' in url:
            url = url + "&"
        else:
            url = url + "?"

        url = url + "XDEBUG_SESSION_START=" + ideKey

        os.system("xdg-open '"+url+"'")

    def _stopBrowser(self, url, ideKey):

        if '?' in url:
            url = url + "&"
        else:
            url = url + "?"

        url = url + "XDEBUG_SESSION_STOP=" + ideKey

        os.system("xdg-open '"+url+"'")

    # ## SESSION MANAGEMENT

    def session_run(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].run()

    def session_run_to_end(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].run(True)

    def session_step_into(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].step_into()

    def session_step_over(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].step_over()

    def session_step_out(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].step_out()

    def session_stop(self, foo=None):
        sessions = self.get_active_sessions()
        if len(sessions) > 0:
            sessions[0].stop()

    # ## BREAKPOINTS

    def _get_breakpoints_filepath(self):
        userDir = os.path.expanduser("~")
        filePath = userDir + "/.local/share/addiks/gedit/xdebug/breakpoints"
        return filePath

    def _save_breakpoints(self):
        filePath = self._get_breakpoints_filepath()
        if not os.path.exists(os.path.dirname(filePath)):
            os.makedirs(os.path.dirname(filePath))
        file_put_contents(filePath, repr(self.get_all_breakpoints()))

    def get_all_breakpoints(self):
        if self._breakpoints is None:
            self._breakpoints = {}
            filePath = self._get_breakpoints_filepath()
            if os.path.exists(filePath):
                self._breakpoints = eval(file_get_contents(filePath))
        return self._breakpoints

    def get_breakpoints(self, filePath):
        breakpoints = self.get_all_breakpoints()
        if filePath not in breakpoints:
            breakpoints[filePath] = []
        return breakpoints[filePath]

    def toggle_breakpoint(self, filePath, line):
        breakpoints = self.get_all_breakpoints()
        if filePath not in breakpoints:
            breakpoints[filePath] = []
        if line in breakpoints[filePath]:
            breakpoints[filePath].remove(line)
            for session in self.get_active_sessions():
                start_new_thread(session.remove_breakpoint_by_file_line, (filePath, line, ))
        else:
            breakpoints[filePath].append(line)
            for session in self.get_active_sessions():
                start_new_thread(session.set_breakpoint, ({
                    'type':     'line',
                    'filename': filePath,
                    'lineno':   line,
                }, ))
        self._save_breakpoints()

    # ## PATHS

    def get_data_dir(self):
        home = expanduser("~")
        basedir = home + "/.local/share/gedit/addiks/xdebug"
        return basedir

    # ## GLADE

    def _getGladeHandler(self):
        if self._glade_handler is None:
            self.__initGlade()
        return self._glade_handler

    def _getGladeBuilder(self):
        if self._glade_builder is None:
            self.__initGlade()
        return self._glade_builder

    def __initGlade(self):
        self._glade_builder = Gtk.Builder()
        self._glade_builder.add_from_file(os.path.dirname(__file__)+"/debugger.glade")
        self._glade_handler = GladeHandler(self, self._glade_builder)
        self._glade_builder.connect_signals(self._glade_handler)
