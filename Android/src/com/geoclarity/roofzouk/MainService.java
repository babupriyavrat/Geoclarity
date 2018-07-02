package com.geoclarity.roofzouk;

import java.text.SimpleDateFormat;
import java.util.Date;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.BatteryManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.os.Message;
import android.preference.Preference;
import android.telephony.SmsMessage;
import android.util.Log;
import android.widget.RemoteViews;

public class MainService extends Service{
    
	public UploadScheduler m_uploadScheduler = null;
	 
	public static final int WHAT_START_UPLOAD = 10;
	
	public static final int NOTIFY_KEY = 3434;
	
	
	int m_nBatteryStatus = 0;
	boolean m_bMustSendLowBattery = false;
	private double m_dLongitude = 0, m_dLatitude = 0;
	
	LocationManager m_locationManager;
	Location m_locationLast = null;
    String m_strCurProvider;
    
	private final IPlaybackService.Stub uploadServiceStub = new IPlaybackService.Stub() {
	};
	
	
    @Override
    public void onCreate() {
        super.onCreate();
        
        //battery usage
    	registerReceiver(battStatusReceiver	, new IntentFilter(Intent.ACTION_BATTERY_CHANGED));
    	
    	createUploadScheduleThread();
    	
    	setupLocationListeners();
    }
   
    public void createUploadScheduleThread(){
    	m_uploadScheduler = new UploadScheduler(this);
		// Start our controlling thread
        final Thread uploadSchedulerThread = new Thread(m_uploadScheduler);
        
        uploadSchedulerThread.start();
        

     }
    
    
    @Override
	public boolean onUnbind(Intent intent) {
		return super.onUnbind(intent);
	}


	@Override
	public void onDestroy() {
		try {
    		unregisterReceiver(battStatusReceiver);
    		//mTimeHandler.removeMessages(WHAT_TIME);
    		//m_locationManager.removeUpdates(locationUpdateReceiver);
    		m_locationManager.removeUpdates(mGpsLocationListener);
    		m_locationManager.removeUpdates(mNetworkLocationListener);
    		
    	} catch (Exception e) {
    		
    	}
		
		super.onDestroy();
	}


	@Override
	public IBinder onBind(Intent arg0) {
		return uploadServiceStub;
	}

	public Handler messageHandler = new Handler() {

		public void handleMessage(Message msg) {
			super.handleMessage(msg);
			if (msg.what == WHAT_START_UPLOAD) {
				
				try {
					SharedPreferences pref = getSharedPreferences(Globals.PREFERENCE_NAME, MainService.MODE_PRIVATE);
					int userIdx = pref.getInt("userIdxKey", 0);
					if (userIdx == 0) return;
					
					
					Bundle bundle = msg.getData();
					
					DeviceLog testLog = new DeviceLog();
					testLog.userIdx = userIdx;
			        testLog.deviceIMEI = Utils.getIEMI(MainService.this);
			        Date nowdate = new Date(bundle.getLong(Globals.KEY_UPLOAD_TIME));
			        String strFormatISO8601 = "yyyy-MM-dd'T'HH:mm:ss";
			        SimpleDateFormat form = new SimpleDateFormat(strFormatISO8601);
			        testLog.timestamp = form.format(nowdate);
			        testLog.latitude = m_dLatitude;
			        testLog.longitude = m_dLongitude;
			        testLog.batteryStatus = m_nBatteryStatus;

			        
			        Log.i("UploadInformation", String.format("Time:%s, (%s, %s), Battery:%d", 
			        		testLog.timestamp, String.valueOf(m_dLatitude), String.valueOf(m_dLongitude), m_nBatteryStatus));
			        
			        InsertDeviceDataLogAsync(testLog);
			        
				} catch (IllegalStateException e) {
					//Toast.makeText(this, "IllegalStateException", 1).show();
					Log.e("error", e.toString());
				} 
	            
			} 
		}
	};

	
    public void InsertDeviceDataLogAsync(final DeviceLog objDeviceLog){
        new AsyncTask<Void, Void, Boolean>(){
            @Override
            protected void onPreExecute() {
               
            };
            @Override
            protected Boolean doInBackground(Void... params) {
            	return Utils.InsertDeviceDataLog(objDeviceLog);
            }
            @Override
            protected void onPostExecute(Boolean result) {
            	Log.e("Result", result? "TRUE":"FALSE");
            }
        }.execute();
    }
    
	/**
	 * Receiver for status of battery
	 */
    private BroadcastReceiver battStatusReceiver = new BroadcastReceiver() {
		
		@Override
		public void onReceive(Context context, Intent intent) {
			int level = intent.getIntExtra(BatteryManager.EXTRA_LEVEL, 0);
	        int scale = intent.getIntExtra(BatteryManager.EXTRA_SCALE, 100);
	        int percent = (level*100)/scale;
	        m_nBatteryStatus = percent;
		}
	};
	
	private void setupLocationListeners(){
    	
    	m_locationManager = (LocationManager)getSystemService(Context.LOCATION_SERVICE);
    	
    	
    	m_locationLast = m_locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
		if (m_locationLast == null){
			m_locationLast = m_locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
		}
		if (m_locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
			m_strCurProvider = LocationManager.GPS_PROVIDER;
		}else{
			m_strCurProvider = LocationManager.NETWORK_PROVIDER;
		}
		m_locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, Globals.GPS_MIN_TIME, Globals.GPS_MIN_DISTANCE, mGpsLocationListener);
		m_locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, Globals.NETWORK_MIN_TIME, Globals.NETWORK_MIN_DISTANCE, mNetworkLocationListener);
    	
    	
    	if (m_locationLast != null) {
    		updateLocation(m_locationLast);
        }
	}
	
	private final LocationListener mGpsLocationListener = new LocationListener() {
    	@Override
    	public void onLocationChanged(Location location) {
    		if (m_locationLast == null) {
    			m_locationLast = location;
    			updateLocation(location);
    		} else {
	    		if (Utils.isBetterLocation(location, m_locationLast) == true) {
					Log.d("LocationFactory.java", "Location Acquired: " + location.toString());
					m_locationLast = location;
					updateLocation(location);
				}
    		}
    	}
    	
    	@Override
    	public void onProviderDisabled(String provider) {
    		m_strCurProvider = LocationManager.NETWORK_PROVIDER;
    		
			Log.e("Service GPS", "Gps disabled");
    	}
    	
    	public void onProviderEnabled(String provider) {
    		m_strCurProvider = LocationManager.GPS_PROVIDER;
			Log.e("Service GPS", "Gps enabled");
    	}
    	
    	@Override
    	public void onStatusChanged(String provider, int status, Bundle extras) {
    		
    	}
    };
    
	private final LocationListener mNetworkLocationListener = new LocationListener() {
    	@Override
    	public void onLocationChanged(Location location) {
    		if (m_locationLast == null) {
    			m_locationLast = location;
    			updateLocation(location);
    		} else {
	    		if (Utils.isBetterLocation(location, m_locationLast)) {
					Log.d("LocationFactory.java", "Location Acquired: " + location.toString());
					m_locationLast = location;
					updateLocation(location);
				}
    		}
    	}
    	
    	@Override
    	public void onProviderDisabled(String provider) {
    		
    	}
    	
    	public void onProviderEnabled(String provider) {
    		
    	}
    	
    	@Override
    	public void onStatusChanged(String provider, int status, Bundle extras) {
    		
    	}
    };
    
    private void updateLocation(Location location) {
		m_dLongitude = location.getLongitude();
		m_dLatitude = location.getLatitude();
		
		SharedPreferences pref = getSharedPreferences(Globals.PREFERENCE_NAME, MainService.MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putFloat(Globals.KEY_LATITUDE, (float)location.getLatitude());
		editor.putFloat(Globals.KEY_LONGITUDE,(float)location.getLongitude());
		editor.commit();
		
    	Log.e("Location-Listener","("+location.getLatitude()+","+location.getLongitude()+")");
     }
    

}