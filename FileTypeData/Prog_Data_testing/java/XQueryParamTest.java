import com.lucasia.xquery.XQuery;
import com.lucasia.xquery.XQueryFileReader;
import junit.framework.Assert;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;
import uk.ac.lkl.common.util.testing.LabelledParameterized;

import java.io.File;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

/**
 * User: ialucas
 * Test to ensure the XQuery unit test works as expected
 * <p/>
 * TODO: what i'd like to do is display each test method as a separate test
 * so we can see what is passing and failing
 */
@RunWith(value = LabelledParameterized.class)
public class XQueryParamTest {

    private String xqFilePath;
    private String label;

    public XQueryParamTest(final String label, final String xqFilePath) {
        this.label = label;
        this.xqFilePath = xqFilePath;
    }


    @Parameterized.Parameters
    /**
     * For example
     * "src/test/xquery/sample/message-business-logic-test.xqy"
     * "src/test/xquery/reference/conform-test.xqy"
     * "src/test/xquery/reference/format-test.xqy"
     * "src/test/xquery/reference/functional-test.xqy"
     */
    public static Collection<Object[]> getParameters() throws FileNotFoundException {
        Collection<Object[]> parameters = new ArrayList<Object[]>();

        XQueryFileReader fileReader = new XQueryFileReader();

        List<File> files = fileReader.recursiveList(XQueryFileReaderTest.TEST_DIR,
                new XQueryFileReaderTest.XQueryTestFileFilter());

        for (File file : files) {
            String testPath = file.getAbsolutePath();
            String label = file.getName();

            parameters.add(new Object[]{label, testPath});
        }

        return parameters;
    }

    public static Collection<Object[]> getParameters2() throws FileNotFoundException {
        Collection<Object[]> parameters = new ArrayList<Object[]>();

        XQueryFileReader fileReader = new XQueryFileReader();

        List<File> files = fileReader.recursiveList(XQueryFileReaderTest.TEST_DIR,
                new XQueryFileReaderTest.XQueryTestFileFilter());

        for (File file : files) {
            String testPath = file.getAbsolutePath();
            String label = file.getName();

            parameters.add(new Object[]{label, testPath});
        }

        return parameters;
    }



    @Test
    public void testXQueryParams() throws Exception {
        final String results = new XQuery().execute(new File(xqFilePath));

        Assert.assertTrue(results.contains("pass")); // ensure at least once success
        Assert.assertFalse(results.contains("fail")); // ensure no failures
    }


}
