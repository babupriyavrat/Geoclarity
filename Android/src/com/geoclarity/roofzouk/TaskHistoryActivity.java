package com.geoclarity.roofzouk;

import java.io.IOException;
import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

@SuppressLint("NewApi")
public class TaskHistoryActivity extends BaseMenuActivity {

	
	SharedPreferences sharedpreferences;
	private int mCurUserId = 0;
	LinearLayout listLayout;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_taskhistory);
		
		//getActionBar().setTitle("MyProfile"); 
		
		listLayout = (LinearLayout) findViewById(R.id.layoutList);
		SharedPreferences sharedpreferences = getSharedPreferences(Globals.PREFERENCE_NAME, Context.MODE_PRIVATE);
		mCurUserId = sharedpreferences.getInt("userIdxKey", 0);
		
		
		(new TaskGetHistory()).execute();
	}
 
	private class TaskGetHistory extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(TaskHistoryActivity.this, "", "Loading task history...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(TaskHistoryActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				listLayout.removeAllViews();
				
				Log.e("Debug", result);
				JSONArray jsonList = new JSONArray(result);
				
				if (jsonList.length() == 0) {
					TextView _view = new TextView(TaskHistoryActivity.this);
					LayoutParams layoutParams = new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT);
					_view.setPadding(10, 100, 10, 100);
					_view.setLayoutParams(layoutParams);
					_view.setText("No task history");
					_view.setBackgroundColor(Color.argb(128, 0, 0, 0));
					_view.setTextColor(Color.rgb(255, 255, 255));
					_view.setGravity(Gravity.CENTER);
					listLayout.addView(_view);
				}
				for (int i=0 ;i<jsonList.length(); i++) {
					TextView _view = new TextView(TaskHistoryActivity.this);
					LayoutParams layoutParams = new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT);
					_view.setPadding(10, 0, 10, 0);
					_view.setLayoutParams(layoutParams);
					_view.setText(jsonList.getString(i));
					
					if (i%2 == 0) { 
						_view.setBackgroundColor(Color.argb(128, 0, 0, 0));
					} else {
						_view.setBackgroundColor(Color.argb(128, 36, 36, 36));
					}
					_view.setTextColor(Color.rgb(255, 255, 255));
					listLayout.addView(_view);
				}
				
			} catch (JSONException e) {
				
				e.printStackTrace();
			}
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "taskhistory/" + mCurUserId; 
			
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpPost httpPost = new HttpPost(url);
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                result = httpclient.execute(httpPost, responseHandler);
                Log.e("Debug", url);
			} catch (IOException e) {
				e.printStackTrace();
			}
			
			return null;
		}
	}
	
}
