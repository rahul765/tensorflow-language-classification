package com.davespanton.nutbar.service.sensor;

public interface SensorChangeListener {
	public void onSensorChanged(float[] values);
	public void setSensorChangeListener(SensorMonitorListener monitorListener);
	public void reset();
}
