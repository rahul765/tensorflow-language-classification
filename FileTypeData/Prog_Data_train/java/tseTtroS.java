package org.luksze;

import org.junit.Test;

import java.util.List;

import static java.util.Arrays.asList;
import static org.junit.Assert.assertArrayEquals;

public class SortTest {
    @Test
    public void simpleSortOverListTest() throws Exception {
        //given
        List<Integer> sourceList = asList(4, 1, 3, 5, 2);

        //when
        sourceList.sort((o1, o2) -> o2.compareTo(o1));

        //then
        assertArrayEquals(asList(5, 4, 3, 2, 1).toArray(), sourceList.toArray());
    }
}
