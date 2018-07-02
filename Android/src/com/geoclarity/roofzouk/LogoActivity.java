package com.geoclarity.roofzouk;

import android.app.Activity;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.Log;
import android.view.Window;

public class LogoActivity extends Activity {

	
	private static String PROJECT_ID = "972007886286"; // Google Cloude Messaging에서 사용하는 프로젝트 식별자

    
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.logo);
        
        
 		doGCMRegister();
 		
        new CountDownTimer(3000, 1000) {

            public void onTick(long millisUntilFinished) {
            		
            }

            public void onFinish() {
              Intent intent = new Intent(LogoActivity.this, LoginActivity.class);
              startActivity(intent);
              finish();
              overridePendingTransition(0, 0);
            }
         }.start();
        
    }
    

    
    public void doGCMRegister() {
    	Intent registrationIntent = new Intent("com.google.android.c2dm.intent.REGISTER");
		registrationIntent.putExtra("app", PendingIntent.getBroadcast(this, 0, new Intent(), 0));
		registrationIntent.putExtra("sender", PROJECT_ID);
		startService(registrationIntent);
		/*
		new AsyncTask<Void, Void, Void>() { 
			@Override
			protected Void doInBackground(Void... params) {
				try {
					GoogleCloudMessaging gcm = GoogleCloudMessaging.getInstance(LoginActivity.this);
					String strPushID = gcm.register(PROJECT_ID);
					Log.e("GCMID", strPushID);
				} catch (Exception e) {
					e.printStackTrace();
				}
				return null;
			}
		}.execute(null, null, null); */
	}
    
}
