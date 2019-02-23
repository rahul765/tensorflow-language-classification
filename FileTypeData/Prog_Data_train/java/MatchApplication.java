package task.test.kataryna.dmytro.match;

import android.app.Application;

import org.testpackage.test_sdk.android.testlib.API;

/**
 * Created by dmytroKataryna on 20.01.16.
 */
public class MatchApplication extends Application {

    @Override
    public void onCreate() {
        super.onCreate();
        API.INSTANCE.init(getApplicationContext());
    }
}
