package org.caching.lombok;

import org.junit.Test;

import static org.assertj.core.api.Assertions.assertThat;

/**
 * Created by iurii.dziuban on 26.09.2016.
 */
public class SynchronizedAndLogExampleTest {

    @Test
    public void test() {
        SynchronizedAndLogExample synchronizedExample = new SynchronizedAndLogExample();
        assertThat(synchronizedExample.print()).isEqualTo("readLock");
    }
}
