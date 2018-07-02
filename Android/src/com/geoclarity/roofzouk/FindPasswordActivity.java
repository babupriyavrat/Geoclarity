package com.geoclarity.roofzouk;

import java.io.IOException;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class FindPasswordActivity extends Activity {

	EditText mEditUserId;
	Button mBtnLogin;
	SharedPreferences sharedpreferences;

	public static final String Name = "nameKey"; 
	public static final String Password = "passwordKey"; 
	public static final String UserIdx = "userIdxKey";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_findpwd);
		
		mEditUserId = (EditText) findViewById(R.id.editUserId);
		
		sharedpreferences = getSharedPreferences("AllApps", Context.MODE_PRIVATE);
		
		mBtnLogin = (Button) findViewById(R.id.btnLogin);
		mBtnLogin.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				
				String userid = mEditUserId.getText().toString();
				if (userid.equals("")) {
					Toast.makeText(FindPasswordActivity.this, "Please input email address", Toast.LENGTH_SHORT).show();
					return;
				}
				new TaskLoginProcess().execute();
			}
		});
		
		findViewById(R.id.txtLogin).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				Intent intent = new Intent(FindPasswordActivity.this, LoginActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0, 0);
			}
		});
		
		findViewById(R.id.txtSignUp).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(FindPasswordActivity.this, RegisterActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0,0);
			}
		});
	}
 
	private class TaskLoginProcess extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(FindPasswordActivity.this, "", "Sending...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(FindPasswordActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				JSONObject jsonObj = new JSONObject(result);
				if (jsonObj.getInt("code") == 0) {
					Toast.makeText(FindPasswordActivity.this, "Email address is incorrect", Toast.LENGTH_SHORT).show();
					return;
				}
				
				Toast.makeText(FindPasswordActivity.this, "New password was sent to your email address", Toast.LENGTH_SHORT).show();
				
			} catch (JSONException e) {
				e.printStackTrace();
			}
			
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "findpwd?email="; 
			url += mEditUserId.getText().toString(); 
			
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
	
	@Override
	public void onBackPressed() {
		Intent intent = new Intent(FindPasswordActivity.this, LoginActivity.class);
		startActivity(intent);
		finish();
		overridePendingTransition(0, 0);
	}
}
