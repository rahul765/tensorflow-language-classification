package com.davespanton.nutbar.service.binder;

import com.davespanton.nutbar.service.AccelerometerListenerService;

public class StubAccelerometerListenerServiceBinder extends AccelerometerListenerServiceBinder {

	private boolean isArmed = false;
	private boolean isTripped = false;
	
	public StubAccelerometerListenerServiceBinder(AccelerometerListenerService service) {
		super(service);
	}

	@Override
	public void onArmed() {
		isArmed = true;
	}

	@Override
	public void onDisarmed() {
		isArmed = isTripped = false;
	}
	
	@Override
	public void onTripped() {
		isTripped = true;
	}
	
	public boolean isArmed() {
		return isArmed;
	}
	
	public boolean isTripped() {
		return isTripped;
	}

}
