package com.studying.decorator.decorators;


import com.studying.decorator.business.Beverage;
import com.studying.decorator.business.CondimentDecorator;

public class Whip extends CondimentDecorator{

    Beverage beverage;

    public Whip(Beverage beverage) {
        this.beverage = beverage;
    }

    @Override
    public String getDescription() {
        return beverage.getDescription() + ", Whip";
    }

    @Override
    public double cost() {
        return 0.56 + beverage.cost();
    }
}
