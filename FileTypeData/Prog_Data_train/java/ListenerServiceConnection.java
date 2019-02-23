package com.davespanton.nutbar.service.connection;

import android.content.ComponentName;
import android.content.ServiceConnection;
import android.os.IBinder;
import com.davespanton.nutbar.activity.NutbarActivity;
import com.davespanton.nutbar.service.binder.ListenerServiceBinder;

public class ListenerServiceConnection implements ServiceConnection {

	private NutbarActivity activity;
	
	private ListenerServiceBinder listenerBinder;
	
	public void setActivity(NutbarActivity nutbarActivity) {
		activity = nutbarActivity;
	}

	@Override
	public void onServiceConnected(ComponentName name, IBinder binder) {
		listenerBinder = (ListenerServiceBinder) binder;
		listenerBinder.onServiceConnection(activity);
	}

	@Override
	public void onServiceDisconnected(ComponentName name) {
		listenerBinder.onServiceDisconnection(activity);
	}

	public boolean isListening() {
		if(listenerBinder != null) 
			return listenerBinder.isListening();
		
		return false;
	}

}
