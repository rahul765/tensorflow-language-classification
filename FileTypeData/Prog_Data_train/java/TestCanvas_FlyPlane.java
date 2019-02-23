package com.example.view;

import android.app.Activity;
import android.os.Bundle;
/**
 * 测试
 * @author Samuel
 *
 */
public class TestCanvas_FlyPlane extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(new FlyPlaneView(this));
	}

}