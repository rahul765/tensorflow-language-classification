package pl.dmichalski.algorithms.min_max_avg;

import org.junit.Assert;
import org.junit.Test;

public class MinMaxAvgTest {

    @Test
    public void testMin() throws Exception {
        MinMaxAvg minMaxAvg = new MinMaxAvg();

        Assert.assertEquals(3, minMaxAvg.min(new int[]{3,5,6,3}));
        Assert.assertEquals(3, minMaxAvg.min(new int[]{7,5,8,3}));
        Assert.assertEquals(1, minMaxAvg.min(new int[]{3,1,6,4}));
    }

    @Test
    public void testMax() throws Exception {
        MinMaxAvg minMaxAvg = new MinMaxAvg();
        int[] numbers = {1, 2, 3, 4, 5};

        Assert.assertEquals(5, minMaxAvg.max(numbers));
    }

    @Test
    public void testAvg() throws Exception {
        MinMaxAvg minMaxAvg = new MinMaxAvg();
        int[] numbers = {1, 2, 3, 4, 5};

        Assert.assertEquals(3, minMaxAvg.avg(numbers), 0);
    }
}