package model;

import java.util.Date;

public class Survey {
    private Date date;
    private double temperature;

    public Survey(Date date, double temperature) {
        this.date = date;
        this.temperature = temperature;
    }

    public Date getDate() {
        return date;
    }

    public double getTemperature() {
        return temperature;
    }

    @Override
    public String toString() {
        return "Survey{" +
                "date=" + date +
                ", temperature=" + temperature +
                "} " + super.toString();
    }
}
