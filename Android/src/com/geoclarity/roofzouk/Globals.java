package com.geoclarity.roofzouk;

public class Globals {

	public static String ApiPath = "http://www.geoclarity.com/roofzouk/index.php/api/";
	//public static String ApiPath = "http://192.168.0.85/index.php/api/";
	
	public static final String SERVICE_CLASS_NAME =	"com.geoclarity.roofzouk.MainService";
	
	public static final String PREFERENCE_NAME =	"com.geoclarity.roofzouk";
	
	public static final String KEY_UPLOAD_INTERVAL =	"UPLOAD_INTERVAL";
	public static final String KEY_LAST_UPLOAD_TIME =	"LAST_UPLOAD_TIME";
	public static final String KEY_UPLOAD_TIME =	"UPLOAD_TIME";
	public static final String KEY_LATITUDE =	"LATITUDE";
	public static final String KEY_LONGITUDE =	"LONGITUDE";
	
	public static final String KEY_GCM_REGID = "GCM_REG_ID";
	
	public static long  GPS_MIN_TIME = 10L*60L*1000L;			//10min
	public static float GPS_MIN_DISTANCE = 500;			//500m
	public static long  NETWORK_MIN_TIME = 10L*60L*1000L;			//1min
	public static float NETWORK_MIN_DISTANCE = 500;			//500m
}
