package org.burnhams.optimiser.neighbourhood;

import org.burnhams.optimiser.Configuration;
import org.burnhams.optimiser.Solution;

import java.util.Random;

public abstract class NeighbourhoodFunction<T, U extends Solution<T>> {

    protected NeighbourhoodFunction(Configuration configuration) {
        this.configuration = configuration;
    }

    protected final Configuration configuration;

    protected final Random random = new Random();

    public abstract U getNeighbour(U candidate);

}