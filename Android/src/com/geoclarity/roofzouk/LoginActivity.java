package com.geoclarity.roofzouk;

import java.io.IOException;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import com.google.android.gms.gcm.GoogleCloudMessaging;

import android.app.Activity;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends Activity {

	EditText mEditUserId, mEditPassword;
	Button mBtnLogin;
	SharedPreferences sharedpreferences;

	public static final String Name = "nameKey"; 
	public static final String Password = "passwordKey"; 
	public static final String UserIdx = "userIdxKey";
	
	LocationManager m_locationManager;
	Location m_locationLast = null;
    String m_strCurProvider;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_login);
		
		mEditUserId = (EditText) findViewById(R.id.editUserId);
		mEditPassword = (EditText) findViewById(R.id.editPassword);
		sharedpreferences = getSharedPreferences(Globals.PREFERENCE_NAME, Context.MODE_PRIVATE);
		//mEditUserId.setText("1admin");
		//mEditPassword.setText("1admin");
		if (sharedpreferences.contains(Name))
	    {
			 mEditUserId.setText(sharedpreferences.getString(Name, ""));

	    }
		if (sharedpreferences.contains(Password))
        {
			mEditPassword.setText(sharedpreferences.getString(Password, ""));

        }
		
	        
		 
		boolean fromMain = getIntent().getBooleanExtra("fromMain", false);
		mBtnLogin = (Button) findViewById(R.id.btnLogin);
		
		mBtnLogin.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {

				String userid = mEditUserId.getText().toString();
				String userpwd = mEditPassword.getText().toString();
				if (userid.equals("")) {
					Toast.makeText(LoginActivity.this, "Please input email address", Toast.LENGTH_SHORT).show();
					return;
				}
				if (userpwd.equals("")) {
					Toast.makeText(LoginActivity.this, "Please input password", Toast.LENGTH_SHORT).show();
					return;
				}
				
				new TaskLoginProcess().execute();
			}
		});
		
		findViewById(R.id.txtForgotPassword).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(LoginActivity.this, FindPasswordActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0, 0);
			}
		});
		
		findViewById(R.id.txtSignUp).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0,0);
			}
		});
		
		setupLocationListeners();
	}
 
	private class TaskLoginProcess extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(LoginActivity.this, "", "Login...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(LoginActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				JSONObject jsonObj = new JSONObject(result);
				if (jsonObj.getInt("userid") == 0) {
					Toast.makeText(LoginActivity.this, "Email or Password is incorrect", Toast.LENGTH_SHORT).show();
					return;
				}
				
				Editor editor = sharedpreferences.edit();
				editor.putString(Name, mEditUserId.getText().toString());
				editor.putString(Password, mEditPassword.getText().toString());
				editor.putInt(UserIdx, jsonObj.getInt("userid"));
				editor.commit();
				
				//loadingDlg.dismiss();
				
				Intent intent = new Intent(LoginActivity.this, MainActivity.class);
				intent.putExtra("userid", jsonObj.getString("userid"));
				startActivity(intent);
				
				finish();
				overridePendingTransition(0, 0);
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
			
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "login?email="; 
			url += mEditUserId.getText().toString(); 
			url += "&pwd=";
			url += mEditPassword.getText().toString();
			
			if (m_locationLast!=null) {
				url += "&userlat=" + m_locationLast.getLatitude();
				url += "&userlong=" + m_locationLast.getLongitude();
			}
			
			// get the gcmid and send it 
			if (sharedpreferences.contains(Globals.KEY_GCM_REGID)) {
				url += "&gcmid=" + sharedpreferences.getString(Globals.KEY_GCM_REGID, "");
			}
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpGet httpget = new HttpGet(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                result = httpclient.execute(httpget, responseHandler);
                Log.e("Debug", url);
			} catch (IOException e) {
				
				e.printStackTrace();
			}
			
			return null;
		}
	}
	
	
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
		m_locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, mGpsLocationListener);
		m_locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, mNetworkLocationListener);
    	
    	
    	if (m_locationLast != null) {
        }
	}
	private final LocationListener mGpsLocationListener = new LocationListener() {
    	@Override
    	public void onLocationChanged(Location location) {
    		if (m_locationLast == null) {
    			m_locationLast = location;
    		} else {
	    		if (Utils.isBetterLocation(location, m_locationLast)) {
					Log.d("LocationFactory.java", "Location Acquired: " + location.toString());
					m_locationLast = location;
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
    		} else {
	    		if (Utils.isBetterLocation(location, m_locationLast)) {
					Log.d("LocationFactory.java", "Location Acquired: " + location.toString());
					m_locationLast = location;
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
    
    @Override
    protected void onDestroy() {
    	try {
    		m_locationManager.removeUpdates(mGpsLocationListener);
    		m_locationManager.removeUpdates(mNetworkLocationListener);
    	} catch (Exception e) {
    	}
    	super.onDestroy();
    }
	
}
