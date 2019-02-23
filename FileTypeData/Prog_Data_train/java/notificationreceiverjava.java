package com.example.sky.attendance;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

public class notificationreceiverjava extends BroadcastReceiver
{

    @Override
    public void onReceive(Context context, Intent intent)
    {
        Intent service1 = new Intent(context, notification1.class);
        context.startService(service1);

    }
}
