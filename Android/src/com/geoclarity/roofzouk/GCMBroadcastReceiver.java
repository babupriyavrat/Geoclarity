package com.geoclarity.roofzouk;


import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.util.Log;
import android.widget.RemoteViews;


public class GCMBroadcastReceiver extends BroadcastReceiver
{
	
	private static String TAG = "RoofZouk GCMBroadcastReceiver";
	
	private static int BOTTOM_LIMIT_NOTIFICATION = 2019;
	private static int TOP_LIMIT_NOTIFICATION = 6019;
	private static int m_NotificationRef = BOTTOM_LIMIT_NOTIFICATION;
	
	@Override	
	public void onReceive(Context context, Intent intent)
 	{
		
		if (intent.getAction().equals("com.google.android.c2dm.intent.REGISTRATION"))
		{
			
			handleRegistration(context, intent);
		}
		else if (intent.getAction().equals("com.google.android.c2dm.intent.RECEIVE"))
 		{
			handleMessage(context, intent);
		}
		
	}
	private void handleRegistration(final Context context, Intent intent)
 	{
		String registration = intent.getStringExtra("registration_id");
		if (registration == null) return;
 		if (intent.getStringExtra("error") != null)
 		{
 			registration = "";
		}
 		else if (intent.getStringExtra("unregistered") != null)
 		{
 			registration = "";
		}
 		
 		if (registration!=null && !registration.equals("")) {
	 		SharedPreferences pref = context.getSharedPreferences(Globals.PREFERENCE_NAME, Activity.MODE_PRIVATE);
			SharedPreferences.Editor editor = pref.edit();
			
			editor.putString(Globals.KEY_GCM_REGID, registration);
			editor.commit();
 		}
	}
	
	private void handleMessage(Context context, Intent intent1)
	{
		String alert = intent1.getStringExtra("msg");
		String str_sosmain_id = intent1.getStringExtra("sosmain_id"); 
		int sosmain_id = Integer.parseInt(str_sosmain_id);
		Log.e("HandleMessage", "Sosmain_id=" + sosmain_id);
		String str_type = intent1.getStringExtra("type");
		
		if (str_type.equals("3")) {
			// send broadcast 
			 Intent intentBroad = new Intent("finish");
		     context.sendBroadcast(intentBroad);
		}
		int pushtype = Integer.parseInt(str_type);
		Intent intent = new Intent(context, SOSActivity.class);
		intent.putExtra("sosmain_id", sosmain_id);
		intent.putExtra("pushtype", pushtype);
		
		NotificationManager mManager = (NotificationManager)context.getSystemService(Context.NOTIFICATION_SERVICE);
		
		Notification notification = new Notification(R.drawable.ic_launcher, "", System.currentTimeMillis());
		notification.flags |= Notification.FLAG_AUTO_CANCEL;	
		notification.defaults |= Notification.DEFAULT_SOUND;
		notification.defaults |= Notification.DEFAULT_VIBRATE;
		PendingIntent pendingIntent = PendingIntent.getActivity(context, m_NotificationRef,	intent,PendingIntent.FLAG_CANCEL_CURRENT);
		notification.contentView = new RemoteViews(context.getPackageName(), R.layout.notification);
		notification.contentView.setTextViewText(R.id.txtTitle, alert);
		
		notification.contentView.setOnClickPendingIntent(m_NotificationRef, pendingIntent);
		notification.when = -System.currentTimeMillis();
		notification.contentIntent = pendingIntent;
		
		mManager.notify(m_NotificationRef, notification);
		
		m_NotificationRef++;
		if (m_NotificationRef >= TOP_LIMIT_NOTIFICATION)
			m_NotificationRef = BOTTOM_LIMIT_NOTIFICATION;
		
	}
		
}
