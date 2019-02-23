package com.davespanton.nutbar.service.sensor;

public class StubSensorChangeMonitor implements SensorChangeListener {
	
	private SensorMonitorListener listener;
	
	private boolean isReset = false;
	
	@Override
	public void onSensorChanged(float[] values) {
		if(listener == null)
			return;
		
		listener.sensorMonitorTripped();
	}
	
	@Override
	public void setSensorChangeListener(SensorMonitorListener monitorListener) {
		listener = monitorListener;
	}

	@Override
	public void reset() {
		isReset = true;
	}
	
	public boolean hasBeenReset() {
		return isReset;
	}
	
}
