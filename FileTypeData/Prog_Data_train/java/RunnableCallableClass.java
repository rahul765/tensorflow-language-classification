package com.kncept.mirage.interfacestest;

import java.util.concurrent.Callable;
import java.util.concurrent.Future;

public class RunnableCallableClass implements Runnable, Callable<Future> {
	@Override public void run() {}
	@Override public Future call() throws Exception { return null;}
}