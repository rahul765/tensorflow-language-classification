package com.juhe.weather;




import com.thinkland.juheapi.common.CommonFun;


import android.app.Application;


public class WeatherApplication extends Application{

	@Override
	public void onCreate() {
		// TODO Auto-generated method stub
		super.onCreate();
		CommonFun.initialize(getApplicationContext());
	}
}
