package course1778.mobileapp.safeMedicare.ParseApplication;

/**
 * Created by jianhuang on 16-02-21.
 */
import android.app.Application;

import com.parse.Parse;
import com.parse.ParseACL;
import com.parse.ParseUser;

public class ParseApplication extends Application {

    public static final String APPLICATION_ID = "gUu8NlxygL7LVfsl6Q8gWfbznTa5LIeFJbyiN4Dc";
    public static final String CLIENT_KEY = "W1vegki58oYEyWJznUQGjOORzLjAOpNPZ4L57EHG";

    @Override
    public void onCreate() {
        super.onCreate();

        // enable local datastore
        //Parse.enableLocalDatastore(this);

        // Add your initialization code here
        Parse.initialize(this, APPLICATION_ID, CLIENT_KEY);

        ParseUser.enableAutomaticUser();
        ParseACL defaultACL = new ParseACL();

        // If you would like all objects to be private by default, remove this
        // line.
        defaultACL.setPublicReadAccess(true);

        ParseACL.setDefaultACL(defaultACL, true);
    }

}
