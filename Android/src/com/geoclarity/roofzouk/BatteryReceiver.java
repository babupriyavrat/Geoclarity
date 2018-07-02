package com.geoclarity.roofzouk;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.IBinder;
import android.util.Log;

public class BatteryReceiver extends BroadcastReceiver{

		public static final String TAG = "NeedmanBatteryReceiver"; 
			
		 @Override public void onReceive(Context context, Intent intent) {  
			 
			 try {
				 Log.i(TAG, "======== Service Manager Start ==============");
			  	if( "android.intent.action.ACTION_POWER_CONNECTED".equals(intent.getAction())) {
/*			  		ComponentName comp = new ComponentName(context.getPackageName(), MainService.class.getName());   
			  		ComponentName service = context.startService(new Intent().setComponent(comp));   
			  		if (null == service){    
			  			Log.e(TAG, "Could not start service " + comp.toString());   
			  		} else 
			  		    Log.i(TAG, "======== Service Service Started ==============");*/
			  		
			  		MainApplication theAppication = (MainApplication)context.getApplicationContext();
					theAppication.startMainService();
			  	} else if ("android.intent.action.BATTERY_OKAY".equals(intent.getAction())) {
			  		MainApplication theAppication = (MainApplication)context.getApplicationContext();
					theAppication.startMainService();
			  	} else {   
			  		Log.e(TAG, "Received unexpected intent " + intent.toString());     
			  	} 
			  	Log.i(TAG, "======== Service Manager End ==============");
			 } catch (Exception e) {
				 e.printStackTrace();
			 }
	 	}

}
