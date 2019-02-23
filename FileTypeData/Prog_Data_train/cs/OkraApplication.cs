﻿using System;
using System.Collections.Generic;
using System.Linq;
using Windows.ApplicationModel.Activation;
using Windows.UI.Xaml;

namespace Okra
{
    public class OkraApplication : Application
    {
        // *** Fields ***

        private OkraBootstrapper _bootstrapper;

        // *** Constructors ***

        public OkraApplication(OkraBootstrapper bootstrapper)
        {
            if (bootstrapper == null)
                throw new ArgumentNullException(nameof(bootstrapper));

            _bootstrapper = bootstrapper;
            bootstrapper.Initialize(false);
        }

        // *** Overriden Base Methods ***

        protected override void OnActivated(IActivatedEventArgs args)
        {
            base.OnActivated(args);
            Activate(args);
        }

        protected override void OnCachedFileUpdaterActivated(CachedFileUpdaterActivatedEventArgs args)
        {
            base.OnCachedFileUpdaterActivated(args);
            Activate(args);
        }

        protected override void OnFileActivated(FileActivatedEventArgs args)
        {
            base.OnFileActivated(args);
            Activate(args);
        }

        protected override void OnFileOpenPickerActivated(FileOpenPickerActivatedEventArgs args)
        {
            base.OnFileOpenPickerActivated(args);
            Activate(args);
        }

        protected override void OnFileSavePickerActivated(FileSavePickerActivatedEventArgs args)
        {
            base.OnFileSavePickerActivated(args);
            Activate(args);
        }

        protected override void OnShareTargetActivated(ShareTargetActivatedEventArgs args)
        {
            base.OnShareTargetActivated(args);
            Activate(args);
        }

        protected override void OnSearchActivated(SearchActivatedEventArgs args)
        {
            base.OnSearchActivated(args);
            Activate(args);
        }

        protected override void OnLaunched(LaunchActivatedEventArgs args)
        {
            base.OnLaunched(args);
            Activate(args);
        }

        // *** Private Methods ***

        private void Activate(IActivatedEventArgs args)
        {
            _bootstrapper.Activate(args);
        }
    }
}
