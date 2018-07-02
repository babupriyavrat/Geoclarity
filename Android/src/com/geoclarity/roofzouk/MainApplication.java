

package com.geoclarity.roofzouk;

import android.app.Application;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.IBinder;
import android.util.Log;

/*
@ReportsCrashes(formKey = "", // will not be used
mailTo = GlobalConst.CRASH_REPORT_EMAIL,
mode = ReportingInteractionMode.TOAST,
resToastText = com.afkary.me.R.string.crash_report)
*/
public class MainApplication extends Application {
	

	private Intent serviceIntent = null;
	private ServiceConnection serviceConnection = null;
	public IPlaybackService myPlaybackService = null;
	
	
	@Override
	public void onCreate() {
        super.onCreate();

        startMainService();
        
 //       ACRA.init(this);		//for reporting crash error
	}
	
		
	
	
	public void startMainService(){
		
		if (Utils.isServiceExisted(this, Globals.SERVICE_CLASS_NAME) != null){
			Log.i("roofzouk info", "service already exist...");
			if (myPlaybackService == null ){
				Log.i("roofzouk info", "service create...");
	        	serviceIntent = new Intent(this, MainService.class);
	        	serviceConnection = new ServiceConnection() {
		        	@Override
					public void onServiceConnected(ComponentName name, IBinder service) {
		        		Log.i("roofzouk info", "connected");
		        		myPlaybackService = IPlaybackService.Stub.asInterface((IBinder) service);
					}
		
					@Override
					public void onServiceDisconnected(ComponentName name) {
						Log.i("roofzouk info", "disconnected");
						myPlaybackService = null;
					}
				};
	        	bindService(serviceIntent, serviceConnection, Context.BIND_AUTO_CREATE);
			}else{
				
				Log.e("roofzouk info", "service real exist...");
			}
        }else{
        	Log.e("roofzouk info", "service real create...");
        	newServiceConnection();
        }
	}
	
	public synchronized void newServiceConnection() {
		Log.i("roofzouk info", "new service ...");
		serviceIntent = new Intent(this, MainService.class);
		
		// Check if service is already running
		
		// Service is not running, start and bind it
		startService(serviceIntent);
		
		serviceConnection = new ServiceConnection() {
        	@Override
			public void onServiceConnected(ComponentName name, IBinder service) {
       			myPlaybackService = IPlaybackService.Stub.asInterface((IBinder) service);
       			Log.i("roofzouk info", "success");
			}

			@Override
			public void onServiceDisconnected(ComponentName name) {
				myPlaybackService = null;
				Log.i("roofzouk info", "fail");
			}
		};
		
		bindService(serviceIntent, serviceConnection, Context.BIND_AUTO_CREATE);
		
	}


}
