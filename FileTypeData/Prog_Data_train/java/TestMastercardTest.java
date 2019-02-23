package getithere;

import org.apache.commons.lang3.RandomUtils;
import org.junit.Test;

import java.io.IOException;


public class TestMastercardTest {

    @Test
    public void cantTransferSomeMoney() throws IOException {
        new MastercardApi().transfer(RandomUtils.nextInt(100, 1000000));
    }


}