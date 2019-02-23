package com.luxoft.bankapp.exceptions;

/**
 * Created by LChlebda on 2016-01-07.
 */
public class RequestNotFoundException extends Exception{
    private String message;

    public RequestNotFoundException() {
        message = "No such request";
    }

    @Override
    public String toString() {
        return message;
    }
}
