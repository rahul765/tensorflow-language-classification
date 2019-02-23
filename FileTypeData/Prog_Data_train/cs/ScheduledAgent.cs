using System.Diagnostics;
using System.Windows;
using Microsoft.Phone.Scheduler;
using Microsoft.Phone.Shell;
using System.IO.IsolatedStorage;
using System;
using Microsoft.Phone.BackgroundAudio;

namespace ScheduledTaskAgent1
{
    public class ScheduledAgent : ScheduledTaskAgent
    {
        /// <remarks>
        /// ScheduledAgent constructor, initializes the UnhandledException handler
        /// </remarks>
        static ScheduledAgent()
        {
            // Subscribe to the managed exception handler
            Deployment.Current.Dispatcher.BeginInvoke(delegate
            {
                Application.Current.UnhandledException += UnhandledException;
            });
        }

        /// Code to execute on Unhandled Exceptions
        private static void UnhandledException(object sender, ApplicationUnhandledExceptionEventArgs e)
        {
            if (Debugger.IsAttached)
            {
                // An unhandled exception has occurred; break into the debugger
                Debugger.Break();
            }
        }

        /// <summary>
        /// Agent that runs a scheduled task
        /// </summary>
        /// <param name="task">
        /// The invoked task
        /// </param>
        /// <remarks>
        /// This method is called when a periodic or resource intensive task is invoked
        /// </remarks>
        protected override void OnInvoke(ScheduledTask task)
        {
            string key = "invokedTimes";
            System.Diagnostics.Debug.WriteLine("Scheduled task invoke.");

            IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;
            if (!settings.Contains(key))
            {
                settings.Add(key, 1);
                settings.Save();
            }
            else if ((int)settings[key] < 2)
            {
                settings[key] = 2;
                settings.Save();
                System.Diagnostics.Debug.WriteLine("half hour.");
            }
            else if ((int)settings[key] == 2)
            {
                //stop music
                ShellToast toast = new ShellToast();
                toast.Title = "StopMusic";
                toast.Content = "StopMusic";
                toast.Show();
                System.Diagnostics.Debug.WriteLine("one hour.");
                BackgroundAudioPlayer.Instance.Stop();
                try
                {
                    ScheduledActionService.Remove("MusicTimer");
                }
                catch (Exception) { }
            }
                       
            
            
            // If debugging is enabled, launch the agent again in one minute.
//#if DEBUG_AGENT
  ScheduledActionService.LaunchForTest(task.Name, TimeSpan.FromSeconds(10));
//#endif
            NotifyComplete();
        }
    }
}