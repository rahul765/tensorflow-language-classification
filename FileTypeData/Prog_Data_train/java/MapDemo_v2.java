/**
 * Copyright (c) 2014 Nokia Corporation and/or its subsidiary(-ies).
 * See the license text file delivered with this project for more information.
 */

package com.example.android.apis.view;

import android.app.Activity;
import android.os.Bundle;

import com.example.android.maps.apis.R;
import com.google.android.gms.maps.GoogleMap;

/**
 * Demonstrates a map defined in XML
 */
public class MapDemo_v2 extends Activity {
	
	GoogleMap mMap;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.mapview_v2);
    }
}
