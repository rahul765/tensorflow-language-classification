package com.eyetracker.mobile;

import android.annotation.SuppressLint;

import org.robolectric.shadows.ShadowLog;

/**
 * Created by fabia on 5/27/2016.
 */
public class TestEyeTrackerApplication extends EyeTrackerApplication {

    @SuppressLint("MissingSuperCall")
    @Override
    public void onCreate() {
        injector = DaggerTestComponent
                .builder()
                .testModule(new TestModule(this))
                .build();

        ShadowLog.stream = System.out;
    }
//
//    @Override
//    public void onTerminate() {
////        super.onTerminate();
//    }

}
