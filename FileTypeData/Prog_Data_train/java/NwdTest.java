package pl.dmichalski.algorithms.nwd;

import org.junit.Test;

import static org.junit.Assert.*;

public class NwdTest {

    @Test
    public void testFindNwd() throws Exception {
        Nwd nwd = new Nwd();

        assertEquals(10, nwd.findNwd(10,20));
        assertEquals(5, nwd.findNwd(5,10));
        assertEquals(20, nwd.findNwd(20,40));
        assertEquals(2, nwd.findNwd(2,4));
        assertEquals(6, nwd.findNwd(6,12));

        assertNotEquals(4, nwd.findNwd(7,12));
        assertNotEquals(6, nwd.findNwd(9,2));
        assertNotEquals(39, nwd.findNwd(2,12));
        assertNotEquals(28, nwd.findNwd(4,52));
        assertNotEquals(89,nwd.findNwd(2,72));
        assertNotEquals(89,nwd.findNwd(0,0));
    }

    @Test(expected = IllegalArgumentException.class)
    public void testThrowing() throws Exception {
        Nwd nwd = new Nwd();
        assertNotEquals(89,nwd.findNwd(-3,0));
    }
}