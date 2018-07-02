package com.geoclarity.roofzouk;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.Polyline;
import com.google.android.gms.maps.model.PolylineOptions;
@SuppressLint("NewApi")
public class MainActivity extends BaseMenuActivity {

    private GoogleMap googleMap;
    private int mCurTaskId = 0, mCurUserId = 0;
    private String mCurUserName, mCustomerPhone = "";
    private TextView mTxtTaskDetail;
    private ProgressDialog sosProgressDialog;
    
    LocationManager m_locationManager;
	Location m_locationLast = null;
    String m_strCurProvider;
    
    
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		//requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_main);
		
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		
		mTxtTaskDetail = (TextView) findViewById(R.id.txtTaskDetail);
		if (googleMap == null) {
            googleMap = ((MapFragment) getFragmentManager().
            findFragmentById(R.id.map)).getMap();
        }
        googleMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
         
		SharedPreferences sharedpreferences = getSharedPreferences(Globals.PREFERENCE_NAME, Context.MODE_PRIVATE);
		mCurUserId = sharedpreferences.getInt("userIdxKey", 0);
		mCurUserName = sharedpreferences.getString("nameKey", "");
		getActionBar().setTitle(mCurUserName); 
		
		
		new AsyncGetTask().execute(); 
		
		 findViewById(R.id.btnDone).setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View arg0) {
					if (mCurTaskId  == 0) {
						Toast.makeText(MainActivity.this, "There is no task to done", Toast.LENGTH_SHORT).show();
					} else {
						new AsyncTaskDone().execute(); 
					}
				}
			});
		 findViewById(R.id.btnCancel).setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View arg0) {
					if (mCurTaskId  == 0) {
						Toast.makeText(MainActivity.this, "There is no task to cancel", Toast.LENGTH_SHORT).show();
					} else {
						new AsyncTaskCancel().execute();
					}
				}
			});
		 
		 findViewById(R.id.btnSos).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				if (mCurTaskId == 0) {
					Toast.makeText(MainActivity.this, "There is no task to sos", Toast.LENGTH_SHORT).show();
				} else {
					new AsyncTaskSos().execute();
				}
			}
		});
		 findViewById(R.id.btnCall).setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View arg0) {
					if (mCurTaskId == 0) {
						Toast.makeText(MainActivity.this, "There is no task to call customer", Toast.LENGTH_SHORT).show();
					} else {
						Intent intent = new Intent(Intent.ACTION_CALL);

						intent.setData(Uri.parse("tel:" + mCustomerPhone));
						startActivity(intent);
					}
				}
			});
		 
		IntentFilter intentFilter = new IntentFilter();
        intentFilter.addAction("finish");
        registerReceiver(mFinishRecevier, intentFilter);
        
        setupLocationListeners();
	}
	
    
	public BroadcastReceiver mFinishRecevier = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            if (intent.getAction().equals("finish")){
            	sosProgressDialog.dismiss();
            	new AsyncGetTask().execute();
            }
        }
    };

    
	private class AsyncTaskSos extends AsyncTask<Void, Void, String> {
		
		@Override
	    protected void onPreExecute() {
	        super.onPreExecute();
	        sosProgressDialog = new ProgressDialog(MainActivity.this);
	        sosProgressDialog.setMessage("your task is being re-scheduled.");
	        sosProgressDialog.setIndeterminate(true);
	        sosProgressDialog.setCancelable(false);
	        sosProgressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "sostask?taskid=" + mCurTaskId+"&userid=" + mCurUserId; 
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpGet httpget = new HttpGet(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                return httpclient.execute(httpget, responseHandler);
			} catch (IOException e) {
				e.printStackTrace();
			}
			return null;
		}
		protected void onPostExecute(String result) {
	        super.onPostExecute(result);
	        // progressDialog.hide();        
	    }
	}
	
	private class AsyncTaskDone extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(MainActivity.this);
	        progressDialog.setMessage("Completing task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "completetask?taskid=" + mCurTaskId; 
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpGet httpget = new HttpGet(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                return httpclient.execute(httpget, responseHandler);
			} catch (IOException e) {
				e.printStackTrace();
			}
			return null;
		}
		protected void onPostExecute(String result) {
	        super.onPostExecute(result);   
	        progressDialog.hide();   
	        Toast.makeText(MainActivity.this, "Succesfully completed current task", Toast.LENGTH_SHORT).show();
	        new AsyncGetTask().execute();
	        
	    }
	}
	
	private class AsyncTaskCancel extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(MainActivity.this);
	        progressDialog.setMessage("Cancelling task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "canceltask?taskid=" + mCurTaskId; 
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpGet httpget = new HttpGet(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                return httpclient.execute(httpget, responseHandler);
			} catch (IOException e) {
				e.printStackTrace();
			}
			return null;
		}
		protected void onPostExecute(String result) {
	        super.onPostExecute(result);   
	        progressDialog.hide();        
	        Toast.makeText(MainActivity.this, "Succesfully cancelled current task", Toast.LENGTH_SHORT).show();
	        new AsyncGetTask().execute();
	    }
	}
	private class AsyncGetTask extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(MainActivity.this);
	        progressDialog.setMessage("Fetching User task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "getCurrentTask?userid=" + mCurUserId; 
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpGet httpget = new HttpGet(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                return httpclient.execute(httpget, responseHandler);
			} catch (IOException e) {
				e.printStackTrace();
			}
			return null;
		}
		protected void onPostExecute(String result) {
	        super.onPostExecute(result);   
	        progressDialog.hide();        
	        
	        try {
	        	googleMap.clear();
	        	
	        	JSONObject jsonObj = new JSONObject(result);
	        	mCurTaskId = jsonObj.getInt("taskid");
	        	
	        	userLocLat = Float.parseFloat(jsonObj.getString("userloclat"));
				userLocLong = Float.parseFloat(jsonObj.getString("userloclong"));
				
				googleMap.moveCamera(CameraUpdateFactory.newLatLng(new  LatLng(userLocLat, userLocLong)));
				
				if (mCurTaskId == 0) {
					mTxtTaskDetail.setText("No current task");
					Toast.makeText(MainActivity.this, "There is no task assign to you", Toast.LENGTH_SHORT).show();
					
					// show current user mark
					final LatLng userPoint = new LatLng(userLocLat , userLocLong);
					Marker startMaker = googleMap.addMarker(
				       		 new MarkerOptions().position(userPoint).icon(BitmapDescriptorFactory.fromResource(R.drawable.sportutilityvehicle)));
					return;
				}
				mTxtTaskDetail.setText(jsonObj.getString("taskdesc"));
				
				mCustomerPhone = jsonObj.getString("phone");
				taskLocLat = Float.parseFloat(jsonObj.getString("taskloclat"));
				taskLocLong = Float.parseFloat(jsonObj.getString("taskloclong"));
		         googleMap.animateCamera(CameraUpdateFactory.zoomTo(10), 2000, null);
		         ShowTaskPath();
		      }
		      catch (Exception e) {
		         e.printStackTrace();
		      }
			 
	    }
	}
	
	private void ShowTaskPath() {
		googleMap.clear();
		
		final LatLng userPoint = new LatLng(userLocLat , userLocLong);
        final LatLng taskPoint = new LatLng(taskLocLat, taskLocLong);
        //Marker TP = googleMap.addMarker(new MarkerOptions().position(userPoint).title("A")); 
        
        Marker startMaker = googleMap.addMarker(
       		 new MarkerOptions().position(userPoint).icon(BitmapDescriptorFactory.fromResource(R.drawable.sportutilityvehicle)));
        
        Marker endMaker = googleMap.addMarker(
       		 new MarkerOptions().position(taskPoint).icon(BitmapDescriptorFactory.fromResource(R.drawable.flag)));
        
        String url = makeURL(taskLocLat, taskLocLong, userLocLat, userLocLong);
        new connectAsyncTask(url).execute();
	}
	private class connectAsyncTask extends AsyncTask<Void, Void, String>{
	    String url;
	    //private ProgressDialog progressDialog;
	    connectAsyncTask(String urlPass){
	        url = urlPass;
	    }
	    @Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        //progressDialog = new ProgressDialog(MainActivity.this);
	        //progressDialog.setMessage("Fetching route, Please wait...");
	        //progressDialog.setIndeterminate(true);
	        //progressDialog.show();
	    }
	    @Override
	    protected String doInBackground(Void... params) {
	        JSONParser jParser = new JSONParser();
	        String json = jParser.getJSONFromUrl(url);
	        return json;
	    }
	    @Override
	    protected void onPostExecute(String result) {
	        super.onPostExecute(result);   
	        //progressDialog.hide();      
	        if(result!=null){
	            drawPath(result);
	        }
	    }
	}
	 public String makeURL (double sourcelat, double sourcelog, double destlat, double destlog ){
	        StringBuilder urlString = new StringBuilder();
	        urlString.append("https://maps.googleapis.com/maps/api/directions/json");
	        urlString.append("?origin=");// from
	        urlString.append(Double.toString(sourcelat));
	        urlString.append(",");
	        urlString
	                .append(Double.toString( sourcelog));
	        urlString.append("&destination=");// to
	        urlString
	                .append(Double.toString( destlat));
	        urlString.append(",");
	        urlString.append(Double.toString( destlog));
	        urlString.append("&sensor=false&mode=driving&alternatives=true");
	        urlString.append("&key=POPULATE_API_KEY");
	        return urlString.toString();
	 }
	 public class JSONParser {

		     InputStream is = null;
		     JSONObject jObj = null;
		     String json = "";
		    // constructor
		    public JSONParser() {
		    }
		    public String getJSONFromUrl(String url) {

		        // Making HTTP request
		        try {
		            // defaultHttpClient
		            DefaultHttpClient httpClient = new DefaultHttpClient();
		            HttpPost httpPost = new HttpPost(url);

		            HttpResponse httpResponse = httpClient.execute(httpPost);
		            HttpEntity httpEntity = httpResponse.getEntity();
		            is = httpEntity.getContent();           

		        } catch (UnsupportedEncodingException e) {
		            e.printStackTrace();
		        } catch (ClientProtocolException e) {
		            e.printStackTrace();
		        } catch (IOException e) {
		            e.printStackTrace();
		        }
		        try {
		            BufferedReader reader = new BufferedReader(new InputStreamReader(
		                    is, "iso-8859-1"), 8);
		            StringBuilder sb = new StringBuilder();
		            String line = null;
		            while ((line = reader.readLine()) != null) {
		                sb.append(line + "\n");
		            }

		            json = sb.toString();
		            is.close();
		        } catch (Exception e) {
		            Log.e("Buffer Error", "Error converting result " + e.toString());
		        }
		        return json;

		    }
		}
	 
	 public void drawPath(String  result) {

		    try {
		            //Tranform the string into a json object
		           final JSONObject json = new JSONObject(result);
		           JSONArray routeArray = json.getJSONArray("routes");
		           JSONObject routes = routeArray.getJSONObject(0);
		           JSONObject overviewPolylines = routes.getJSONObject("overview_polyline");
		           String encodedString = overviewPolylines.getString("points");
		           List<LatLng> list = decodePoly(encodedString);
		           Polyline line = googleMap.addPolyline(new PolylineOptions()
		                                    .addAll(list)
		                                    .width(12)
		                                    .color(Color.parseColor("#05b1fb"))//Google maps blue color
		                                    .geodesic(true)
		                    );
		           /*
		           for(int z = 0; z<list.size()-1;z++){
		                LatLng src= list.get(z);
		                LatLng dest= list.get(z+1);
		                Polyline line = mMap.addPolyline(new PolylineOptions()
		                .add(new LatLng(src.latitude, src.longitude), new LatLng(dest.latitude,   dest.longitude))
		                .width(2)
		                .color(Color.BLUE).geodesic(true));
		            }
		           */
		    } 
		    catch (JSONException e) {

		    }
		} 
	 
	 private List<LatLng> decodePoly(String encoded) {

		    List<LatLng> poly = new ArrayList<LatLng>();
		    int index = 0, len = encoded.length();
		    int lat = 0, lng = 0;

		    while (index < len) {
		        int b, shift = 0, result = 0;
		        do {
		            b = encoded.charAt(index++) - 63;
		            result |= (b & 0x1f) << shift;
		            shift += 5;
		        } while (b >= 0x20);
		        int dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
		        lat += dlat;

		        shift = 0;
		        result = 0;
		        do {
		            b = encoded.charAt(index++) - 63;
		            result |= (b & 0x1f) << shift;
		            shift += 5;
		        } while (b >= 0x20);
		        int dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
		        lng += dlng;

		        LatLng p = new LatLng( (((double) lat / 1E5)),
		                 (((double) lng / 1E5) ));
		        poly.add(p);
		    }

		    return poly;
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
		    			if (location.getLatitude() == m_locationLast.getLatitude() && location.getLongitude() == m_locationLast.getLongitude()) return;
						Log.d("LocationFactory.java", "Location Acquired: " + location.toString());
						m_locationLast = location;
						
						userLocLat = (float)m_locationLast.getLatitude();
						userLocLong = (float)m_locationLast.getLongitude();
						ShowTaskPath();
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
		    			
		    			if (location.getLatitude() == m_locationLast.getLatitude() && location.getLongitude() == m_locationLast.getLongitude()) return;
		    			userLocLat = (float)m_locationLast.getLatitude();
						userLocLong = (float)m_locationLast.getLongitude();
						ShowTaskPath();
						
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
	    		unregisterReceiver(mFinishRecevier);
	    	} catch (Exception e) {
	    	}
	    	super.onDestroy();
	    }
	    
	    
}
