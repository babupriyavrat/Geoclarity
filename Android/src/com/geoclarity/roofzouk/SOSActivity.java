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
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.TextureView;
import android.view.View;
import android.view.Window;
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
public class SOSActivity extends Activity {

    private GoogleMap googleMap;
    private int mSosMainId = 0, mCurUserId = 0, mCurTaskId = 0, mPushType = 1;
    
    private TextView mTxtTaskDetail;
    private ProgressDialog sosProgressDialog;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_sos);
		
		mTxtTaskDetail = (TextView) findViewById(R.id.txtTaskDetail);
		if (googleMap == null) {
            googleMap = ((MapFragment) getFragmentManager().
            findFragmentById(R.id.map)).getMap();
        }
        googleMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
         
		SharedPreferences sharedpreferences = getSharedPreferences(Globals.PREFERENCE_NAME, Context.MODE_PRIVATE);
		mCurUserId = sharedpreferences.getInt("userIdxKey", 0);
		mSosMainId = getIntent().getIntExtra("sosmain_id", 0);
		mPushType = getIntent().getIntExtra("pushtype", 1);
		
		String mCurUserName = sharedpreferences.getString("nameKey", "");
		getActionBar().setTitle(mCurUserName); 
		
		new AsyncGetTask().execute(); 
		
		
		 findViewById(R.id.btnDone).setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View arg0) {
					if (mCurTaskId  == 0) {
						Toast.makeText(SOSActivity.this, "There is no sos task", Toast.LENGTH_SHORT).show();
					} else {
						new AsyncTaskDone().execute(); 
					}
					
				}
			});
		 findViewById(R.id.btnCancel).setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View arg0) {
					if (mCurTaskId  == 0) {
						Toast.makeText(SOSActivity.this, "There is no sos task", Toast.LENGTH_SHORT).show();
					} else {
						new AsyncTaskCancel().execute();
					}
				}
			});
		 
		 
	}
	
	private class AsyncTaskDone extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(SOSActivity.this);
	        progressDialog.setMessage("Response SOS Task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "sostaskresponse?sosmain_id=" + mSosMainId + "&user_id=" + mCurUserId + "&rescode=1";
			Log.e("Response SOS", url);
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
	        Toast.makeText(SOSActivity.this, "You can get this sos task later.", Toast.LENGTH_SHORT).show();
	        finish();
	    }
	}
	
	private class AsyncTaskCancel extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(SOSActivity.this);
	        progressDialog.setMessage("Response SOS Task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "sostaskresponse?sosmain_id=" + mSosMainId + "&user_id=" + mCurUserId + "&rescode=0";
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
	        Toast.makeText(SOSActivity.this, "Succesfully ignored the sos task", Toast.LENGTH_SHORT).show();
	        finish();
	    }
	}
	
	private class AsyncGetTask extends AsyncTask<Void, Void, String> {
		private ProgressDialog progressDialog;
		@Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(SOSActivity.this);
	        progressDialog.setMessage("Fetching User task...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
	    }
		@Override
		protected String doInBackground(Void... params) {
			String url = Globals.ApiPath + "getSosTask?sosmain_id=" + mSosMainId + "&user_id=" + mCurUserId;
			Log.e("SosGetTask", url);
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
				if (mCurTaskId == 0) {
					mTxtTaskDetail.setText(jsonObj.getString("taskdesc"));
					findViewById(R.id.btnDone).setVisibility(View.GONE);
					findViewById(R.id.btnCancel).setVisibility(View.GONE);
					
					return;
				}
				mTxtTaskDetail.setText(jsonObj.getString("taskdesc"));
				
				float userLocLong, userLocLat, taskLocLong, taskLocLat;
				userLocLat = Float.parseFloat(jsonObj.getString("userloclat"));
				userLocLong = Float.parseFloat(jsonObj.getString("userloclong"));
				taskLocLat = Float.parseFloat(jsonObj.getString("taskloclat"));
				taskLocLong = Float.parseFloat(jsonObj.getString("taskloclong"));
				
		         googleMap.moveCamera(CameraUpdateFactory.newLatLng(new  LatLng(taskLocLat, taskLocLong)));
		         
		         final LatLng userPoint = new LatLng(userLocLat , userLocLong);
		         final LatLng taskPoint = new LatLng(taskLocLat, taskLocLong);
		         //Marker TP = googleMap.addMarker(new MarkerOptions().position(userPoint).title("A")); 
		         
		         String url = makeURL(taskLocLat, taskLocLong, userLocLat, userLocLong);
		         new connectAsyncTask(url).execute();
		         googleMap.animateCamera(CameraUpdateFactory.zoomTo(10), 2000, null);
		         
		         
		         Marker startMaker = googleMap.addMarker(
		        		 new MarkerOptions().position(userPoint).icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_GREEN)));
		         
		         Marker endMaker = googleMap.addMarker(
		        		 new MarkerOptions().position(taskPoint).icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_RED)));
		      }
		      catch (Exception e) {
		         e.printStackTrace();
		      }
			 
	    }
	}
	private class connectAsyncTask extends AsyncTask<Void, Void, String>{
	    private ProgressDialog progressDialog;
	    String url;
	    connectAsyncTask(String urlPass){
	        url = urlPass;
	    }
	    @Override
	    protected void onPreExecute() {
	        // TODO Auto-generated method stub
	        super.onPreExecute();
	        progressDialog = new ProgressDialog(SOSActivity.this);
	        progressDialog.setMessage("Fetching route, Please wait...");
	        progressDialog.setIndeterminate(true);
	        progressDialog.show();
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
	        progressDialog.hide();        
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
	        urlString.append("&key=AIzaSyAbLgfQr6-ils7tHMyDkrmTC6HX8PCzSwU");
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
}
