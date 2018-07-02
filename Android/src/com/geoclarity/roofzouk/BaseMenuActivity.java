package com.geoclarity.roofzouk;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;

public class BaseMenuActivity extends Activity {

	float userLocLong, userLocLat, taskLocLong, taskLocLat;
	
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
	    MenuInflater inflater = getMenuInflater();
	    inflater.inflate(R.menu.main, menu);
	    return true;
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
	    // Handle item selection
	    switch (item.getItemId()) {
	        case R.id.action_logout:
	            
	        	Intent intent = new Intent(BaseMenuActivity.this, LoginActivity.class);
	        	startActivity(intent);
	        	finish();
	        	
	            return true;
	        case R.id.action_profile:
	        	Intent intentProfile = new Intent(BaseMenuActivity.this, MyProfileActivity.class);
	        	startActivity(intentProfile);
	        	//finish();
	        	
	            return true;
	        case R.id.action_history:
	        	Intent intentTaskHistory = new Intent(BaseMenuActivity.this, TaskHistoryActivity.class);
	        	startActivity(intentTaskHistory);
	        	
	        	return true;
	        	
	        case R.id.action_googlemap:
	        	String url = "http://maps.google.com/maps?saddr="+userLocLat+","+userLocLong+"&daddr="+taskLocLat+","+taskLocLong+"&mode=driving";
	        	Intent intentGoogleMap = new Intent(android.content.Intent.ACTION_VIEW, Uri.parse(url));
	        	intentGoogleMap.setClassName("com.google.android.apps.maps", "com.google.android.maps.MapsActivity");
	        	startActivity(intentGoogleMap);
	        	return true;
	        default:
	            return super.onOptionsItemSelected(item);
	    }
	}
}
