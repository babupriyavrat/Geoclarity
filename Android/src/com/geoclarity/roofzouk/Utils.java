package com.geoclarity.roofzouk;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

import android.app.ActivityManager;
import android.app.ActivityManager.RunningServiceInfo;
import android.app.PendingIntent;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationManager;
import android.telephony.SmsManager;
import android.telephony.TelephonyManager;
import android.util.Log;

public class Utils {
	
    /**
     * check if gps is one or off
     * @return on-true, off-false
     */
    public static boolean getGpsStatus(Context context){
    	LocationManager lm = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);
    	boolean bGpsOn = lm.isProviderEnabled (LocationManager.GPS_PROVIDER);
    	return bGpsOn;
    }
    
    /**
     * get IMEI string
     * @param context
     * @return
     */
    public static String getIEMI(Context context){
    	TelephonyManager mTelephonyMgr = (TelephonyManager) context.getSystemService(Context.TELEPHONY_SERVICE); 
    	return mTelephonyMgr.getDeviceId();
    	//return "354581049765382";
    	
    }
    
    /**
     * get mobile number of SIM
     * @param context
     * @return
     */
    public static String getMobileNumber(Context context) {
    	TelephonyManager tm = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE); 
    	return tm.getLine1Number();
    }
    
    public static boolean InsertDeviceDataLog(DeviceLog objDeviceLog){
    	String url = Globals.ApiPath + "insertlocation"; 
		
		HttpClient httpclient = new DefaultHttpClient();
		try {
			HttpPost httpPost = new HttpPost(url);
			
			ArrayList<NameValuePair> postParameters = new ArrayList<NameValuePair>();
			postParameters.add(new BasicNameValuePair("userid", objDeviceLog.userIdx + ""));
			postParameters.add(new BasicNameValuePair("lat", objDeviceLog.latitude + ""));
			postParameters.add(new BasicNameValuePair("long", objDeviceLog.longitude + ""));
			postParameters.add(new BasicNameValuePair("battery", objDeviceLog.batteryStatus + ""));
			httpPost.setEntity(new UrlEncodedFormEntity(postParameters));
			
			ResponseHandler<String> responseHandler = new BasicResponseHandler();
            httpclient.execute(httpPost, responseHandler);
            Log.e("Debug", url);
		} catch (IOException e) {
			
			e.printStackTrace();
		}
		
    	/*
    	SoapSerializationEnvelope soapEnvelope = new SoapSerializationEnvelope(SoapEnvelope.VER11);
        soapEnvelope.implicitTypes = true;
        soapEnvelope.dotNet = true;
        SoapObject soapReq = new SoapObject("http://tempuri.org/","InsertDeviceDataLog");
        soapEnvelope.addMapping("http://tempuri.org/","objDeviceLog",new DeviceLog().getClass());
        MarshalFloat marshalFloat = new MarshalFloat();
        marshalFloat.register(soapEnvelope);
        soapReq.addProperty("objDeviceLog",objDeviceLog);
        soapEnvelope.setOutputSoapObject(soapReq);
        HttpTransportSE httpTransport = new HttpTransportSE(url,timeOut);
        try{
            
            httpTransport.call("http://tempuri.org/InsertDeviceDataLog", soapEnvelope);
            
            Object retObj = soapEnvelope.bodyIn;
            if (retObj instanceof SoapFault){
                Log.e("SOAP-FAULT", "SOAP-FAULT");
            }else{
                SoapObject result=(SoapObject)retObj;
                if (result.getPropertyCount() > 0){
                    Object obj = result.getProperty(0);
                    if (obj != null && obj.getClass().equals(SoapPrimitive.class)){
                        SoapPrimitive j =(SoapPrimitive) obj;
                        boolean resultVariable = Boolean.parseBoolean(j.toString());
                        return resultVariable;
                    }else if (obj!= null && obj instanceof Boolean){
                        boolean resultVariable = (Boolean) obj;
                        return resultVariable;
                    }
                }
            }
        }catch (Exception e) {
            e.printStackTrace();
        }*/
        return false;
    }
    
	public static ComponentName isServiceExisted(Context context, String className)
	{
		ActivityManager activityManager = (ActivityManager) context.getSystemService(Context.ACTIVITY_SERVICE);
		
		List<ActivityManager.RunningServiceInfo> serviceList = 
			activityManager.getRunningServices(Integer.MAX_VALUE);
		
		if(!(serviceList.size() > 0))
		{
			return null;
		}
		
		for(int i = 0; i < serviceList.size(); i++)
		{
			RunningServiceInfo serviceInfo = serviceList.get(i);
			ComponentName serviceName = serviceInfo.service;
			
			if(serviceName.getClassName().equals(className))
			{
				return serviceName;
			}
		}
		return null;
	}
	
	/**
	 * 
	 * @param oldTime	in ms
	 * @param newTime	in ms
	 * @return
	 */
	public static long calcDiffMinutes(long oldTime, long newTime){
		long _1min = 1000L*60L;
		return (newTime - oldTime) /_1min; 
	}
	
	private static final int TWO_MINUTES = 1000 * 60 * 2;
	 
	public static boolean isBetterLocation(Location location, Location currentBestLocation) {
	    if (currentBestLocation == null) {
	        return true;
	    }
	 
	    long timeDelta = location.getTime() - currentBestLocation.getTime();
	    boolean isSignificantlyNewer = timeDelta > TWO_MINUTES;
	    boolean isSignificantlyOlder = timeDelta < -TWO_MINUTES;
	    boolean isNewer = timeDelta > 0;
	 
	    if (isSignificantlyNewer) {
	        return true;
	    } else if (isSignificantlyOlder) {
	        return false;
	    }
	 
	    int accuracyDelta = (int) (location.getAccuracy() - currentBestLocation.getAccuracy());
	    boolean isLessAccurate = accuracyDelta > 0;
	    boolean isMoreAccurate = accuracyDelta < 0;
	    boolean isSignificantlyLessAccurate = accuracyDelta > 200;
	 
	    boolean isFromSameProvider = isSameProvider(location.getProvider(),
	            currentBestLocation.getProvider());
	 
	    if (isMoreAccurate) {
	        return true;
	    } else if (isNewer && !isLessAccurate) {
	        return true;
	    } else if (isNewer && !isSignificantlyLessAccurate && isFromSameProvider) {
	        return true;
	    }
	    return false;
	}
	 
	private static boolean isSameProvider(String provider1, String provider2) {
	    if (provider1 == null) {
	      return provider2 == null;
	    }
	    return provider1.equals(provider2);
	}
	
	public static boolean sendSMS(Context context, String strPhoneNo, String strMsg) {
		try {
			SmsManager smsManager = SmsManager.getDefault();
			PendingIntent sentPI;
			String SENT = "SMS_SENT";
			sentPI = PendingIntent.getBroadcast(context, 0,new Intent(SENT), 0);
			
			smsManager.sendTextMessage(strPhoneNo, null, strMsg, null, null);
			return true;
		  } catch (Exception e) {
			e.printStackTrace();
			return false;
		  }
	}
}