package com.davespanton.nutbar.activity.menu;

import android.app.Activity;
import android.view.Menu;
import android.view.MenuItem;

public class StubOptionsMenuDelegate extends OptionsMenuDelegate {

	private boolean populateCalled = false;
	
	private MenuItem lastSelectedItem;
	
	@Override
	public boolean populateOptionsMenu(Activity activity, Menu menu) {
		populateCalled = true;
		return super.populateOptionsMenu(activity, menu);
	}
	
	public boolean hasBeenPopulated() {
		return populateCalled;
	}
	
	@Override
	public void onOptionsItemSelected(MenuItem item) {
		lastSelectedItem = item;
	}
	
	public MenuItem getLastSelectedItem() {
		return lastSelectedItem;
	}
}
