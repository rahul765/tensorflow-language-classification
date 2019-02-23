package com.sankeerthan.display;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;

public class SankeerthanDialog {
	public static AlertDialog getAlertDialog(Context context, String message) {
		AlertDialog.Builder builder = new AlertDialog.Builder(context);
		builder.setMessage(message)
		       .setCancelable(true)
			   .setPositiveButton("OK", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog,int id) {
					}
				  });
   	    AlertDialog alert = builder.create();
		return alert;
	}}
