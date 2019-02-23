package com.davespanton.nutbar.alarms;

import android.app.PendingIntent;
import android.content.Context;
import com.google.inject.assistedinject.Assisted;
import com.xtremelabs.robolectric.Robolectric;

public class StubSMSSendingAlarm extends SMSSendingAlarm {

	private int tripCount = 0;

    public StubSMSSendingAlarm() {
        this(Robolectric.application.getApplicationContext());
    }

    public StubSMSSendingAlarm(@Assisted Context context) {
        super(context);
    }

    @Override
	public void tripAlarm() {
		super.tripAlarm();
        tripCount++;
	}

	public int getTripCount() {
		return tripCount;
	}
}
