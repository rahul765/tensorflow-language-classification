package com.davespanton.nutbar.service.binder;

import com.davespanton.nutbar.service.AccelerometerListenerService;

public class StubAccelerometerBinderBuilder extends AccelerometerBinderBuilder {

	@Override
	public AccelerometerListenerServiceBinder build(AccelerometerListenerService service) {
		return new StubAccelerometerListenerServiceBinder(service);
	}

}
