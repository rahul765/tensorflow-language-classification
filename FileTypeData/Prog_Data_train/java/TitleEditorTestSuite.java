package TestClasses.TestTitleEditorClasses;

/**
 * Created by Konrad on 2017-08-10.
 */

import junit.framework.TestSuite;

public class TitleEditorTestSuite {
    public static void addTests(TestSuite suite) {
        suite.addTestSuite(HTMLTitleEditorTest.class);
    }
}
