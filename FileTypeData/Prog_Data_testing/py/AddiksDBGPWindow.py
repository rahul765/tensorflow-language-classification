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

from gi.repository import Gtk, GObject, Gedit, Gio
# from helpers import *

from AddiksDBGPApp import AddiksDBGPApp


class AddiksDBGPWindow(GObject.Object, Gedit.WindowActivatable):
    window = GObject.property(type=Gedit.Window)

    def __init__(self):
        GObject.Object.__init__(self)

    def do_activate(self):
        AddiksDBGPApp.get().register_window(self)

        action = Gio.SimpleAction(name="switch_listener")
        action.connect('activate', AddiksDBGPApp.get().switch_listening)
        self.window.add_action(action)

        # plugin_path = os.path.dirname(__file__)
        actions = [
            ['DebugAction',                "Debugging",                           "",    None],
            ['StartListeningAction',       "Start listening for debug-sessions",  "",    AddiksDBGPApp.get().start_listening],
            ['StopListeningAction',        "Stop listening for debug-sessions",   "",    AddiksDBGPApp.get().stop_listening],
            ['ManageProfilesAction',       "Manage profiles",                     "",    AddiksDBGPApp.get().show_profile_manager],
            ['SessionStopAction',          "Stop session",                        "",    AddiksDBGPApp.get().session_stop],
            ['SessionStepIntoAction',      "Step into",                           "",  AddiksDBGPApp.get().session_step_into],
            ['SessionStepOverAction',      "Step over",                           "",  AddiksDBGPApp.get().session_step_over],
            ['SessionStepOutAction',       "Step out",                            "",  AddiksDBGPApp.get().session_step_out],
            ['SessionRunAction',           "Run",                                 "",  AddiksDBGPApp.get().session_run],
            ['SessionRunToEndAction',      "Run to end (ignore breakpoints)",     "",  AddiksDBGPApp.get().session_run_to_end],
        ]

        self._actions = Gtk.ActionGroup("AddiksDBGPMenuActions")
        for actionName, title, shortcut, callback in actions:
            self._actions.add_actions([
                (actionName, Gtk.STOCK_INFO, title, shortcut, "", callback),
            ])

        for profileName in AddiksDBGPApp.get().get_profile_manager().get_profiles():

            menuItem = Gtk.MenuItem()
            menuItem._addiks_profile_name = profileName
            menuItem.set_label("Send start-debugging request to: "+profileName)
            menuItem.connect("activate", self.on_run_session_per_menu)
            menuItem.show()

        for profileName in AddiksDBGPApp.get().get_profile_manager().get_profiles():

            menuItem = Gtk.MenuItem()
            menuItem._addiks_profile_name = profileName
            menuItem.set_label("Send stop-debugging request to: "+profileName)
            menuItem.connect("activate", self.on_stop_session_per_menu)
            menuItem.show()

        if AddiksDBGPApp.get().does_listen():
            self.set_listen_menu_set_started()
        else:
            self.set_listen_menu_set_stopped()

    def on_run_session_per_menu(self, menuItem=None):
        # profileName = menuItem._addiks_profile_name
        profile = AddiksDBGPApp.get().get_profile_manager().get_profile()
        AddiksDBGPApp.get()._runBrowser(profile['url'], profile['dbgp_ide_key'])

    def on_stop_session_per_menu(self, menuItem=None):
        # profileName = menuItem._addiks_profile_name
        profile = AddiksDBGPApp.get().get_profile_manager().get_profile()
        AddiksDBGPApp.get()._stopBrowser(profile['url'], profile['dbgp_ide_key'])

    def do_deactivate(self):
        AddiksDBGPApp.get().unregister_window(self)

    def do_update_state(self):
        pass

    def get_accel_group(self):
        return self._ui_manager.get_accel_group()

    def set_listen_menu_set_started(self):
        actionStart = self._actions.get_action("StartListeningAction")
        actionStop = self._actions.get_action("StopListeningAction")
        actionStart.set_visible(False)
        actionStop.set_visible(True)

    def set_listen_menu_set_stopped(self):
        actionStart = self._actions.get_action("StartListeningAction")
        actionStop = self._actions.get_action("StopListeningAction")
        actionStart.set_visible(True)
        actionStop.set_visible(False)
