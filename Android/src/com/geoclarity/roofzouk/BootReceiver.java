package com.geoclarity.roofzouk;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

public class BootReceiver extends BroadcastReceiver{

		 public static final String TAG = "NeedmanBootReceiver"; 
		 
		 @Override public void onReceive(Context context, Intent intent) {  
			 
			 try {
				 Log.i(TAG, "======== Service Manager Start ==============");
			  	if( "android.intent.action.BOOT_COMPLETED".equals(intent.getAction())) {
			  		ComponentName comp = new ComponentName(context.getPackageName(), MainService.class.getName());   
			  		ComponentName service = context.startService(new Intent().setComponent(comp));   
			  		if (null == service){    
			  			Log.e(TAG, "Could not start service " + comp.toString());   
			  		} else 
			  		    Log.i(TAG, "======== Service Service Started ==============");
			  	} else {   
			  		Log.e(TAG, "Received unexpected intent " + intent.toString());     
			  	} 
			  	Log.i(TAG, "======== Service Manager End ==============");
			 } catch (Exception e) {
				 
			 }
	 	}

}
