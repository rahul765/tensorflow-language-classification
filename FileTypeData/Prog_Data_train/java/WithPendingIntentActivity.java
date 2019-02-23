package dev.ronlemire.notifications;

import android.app.Activity;
import android.app.NotificationManager;
import android.os.Bundle;

public class WithPendingIntentActivity extends Activity {
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.with_pending_intent_activity);

		// ---look up the notification manager service---
		NotificationManager nm = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);

		// ---cancel the notification that we started
		nm.cancel(getIntent().getExtras().getInt("notificationID"));
	}
}
