
package com.geoclarity.roofzouk;

import java.text.SimpleDateFormat;
import java.util.Date;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Message;
import android.util.Log;

class UploadScheduler implements Runnable {
	
	Context mContext;
	
	boolean m_bRunning;
	
	private long _MINUTUE_1 = 1000L * 60L;

	
	public UploadScheduler(Context context){
		mContext = context;
		m_bRunning = true;
	}
	
	@Override
	public void run() {
		
		while(true){
			try{
				String format ="yyyyMMMdd HH:mm:ss";
				SimpleDateFormat dateFormat = new SimpleDateFormat(format);
				long _lCurrentTime = System.currentTimeMillis();
				String strCurDate = dateFormat.format(new Date(_lCurrentTime));
				
				SharedPreferences _pref = mContext.getSharedPreferences(
	    				Globals.PREFERENCE_NAME, Activity.MODE_PRIVATE);

				long _lLastUploadTime = _pref.getLong(Globals.KEY_LAST_UPLOAD_TIME, -1);
				int _nInterval =  _pref.getInt(Globals.KEY_UPLOAD_INTERVAL, 3);
				long _lDiffMin = Utils.calcDiffMinutes(_lLastUploadTime, _lCurrentTime);
				
				String strLog = String.format("LastUploadTime:%s Interval:%d, DiffByMin:%s", 
						String.valueOf(_lLastUploadTime), _nInterval, String.valueOf(_lDiffMin));
				if (_lDiffMin > 0 ) {
				Message msg = new Message();
					msg.what = MainService.WHAT_START_UPLOAD;
					Bundle data = new Bundle();
					data.putLong(Globals.KEY_UPLOAD_TIME, _lCurrentTime);
					msg.setData(data);
					((MainService)mContext).messageHandler.sendMessage(msg);
	
					Log.i("service process start", "Process - "+strCurDate +" "+strLog);
				} else {
					Log.i("service pass", "Pass - "+strCurDate +" "+strLog);
				}
					    		
		    	Thread.sleep(_MINUTUE_1);			//proc cycle = 1 min
		    	
			}catch(Exception e){
				Log.e("error", e.toString());
			}
			
		}

		
		
	}
	
	
	
}
