package org.burnhams.optimiser.neighbourhood;

import org.burnhams.optimiser.Configuration;
import org.burnhams.optimiser.Solution;

public class RandomSwapNeighbour<T, U extends Solution<T>> extends NeighbourhoodFunction<T,U> {

    public RandomSwapNeighbour(Configuration configuration) {
        super(configuration);
    }

    @Override
    public U getNeighbour(U candidate) {
        U result = (U)candidate.clone();
        int size = candidate.swappableSize();
        boolean swapped = false;
        while (!swapped) {
            int from = random.nextInt(size);
            int to = random.nextInt(size);
            swapped = result.swap(from, to);
        }
        return result;
    }
}
