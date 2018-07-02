package com.geoclarity.roofzouk;

import java.io.IOException;
import java.util.*;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.HttpParams;
import org.json.JSONArray;
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
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

public class RegisterActivity extends Activity {

	EditText mEditUserId, mEditPassword, mEditUserName, mEditHomePhone, mEditMobilePhone, mEditVehicleReg, mEditCompanyName;
	Spinner mSpinerUserRole, mSpinerVehicleType, mSpinerSupervisor;
	Button mBtnLogin;
	SharedPreferences sharedpreferences;
	ArrayList<Integer> ListSupervisorIds = new ArrayList<Integer>();
	ArrayList<String> ListSupervisorNames = new ArrayList<String>();

	Boolean isValidCompanyName = false;
	public static final String Name = "nameKey"; 
	public static final String Password = "passwordKey"; 
	public static final String UserIdx = "userIdxKey";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_register);
		
		mEditUserId = (EditText) findViewById(R.id.editUserId);
		mEditPassword = (EditText) findViewById(R.id.editPassword);
		mEditCompanyName = (EditText) findViewById(R.id.editCompanyName);
		mEditHomePhone = (EditText) findViewById(R.id.editHomePhone);
		mEditMobilePhone = (EditText) findViewById(R.id.editMobilePhone);
		mEditVehicleReg = (EditText) findViewById(R.id.editVehicleReg);
		mEditUserName = (EditText) findViewById(R.id.editUserName);
		
		mSpinerUserRole = (Spinner)findViewById(R.id.spinnerUserRole);
		ArrayAdapter<CharSequence> adapterUserRole = ArrayAdapter.createFromResource(this, R.array.UserRole_Values, android.R.layout.simple_spinner_item);
		adapterUserRole.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		mSpinerUserRole.setAdapter(adapterUserRole);
		
		mSpinerVehicleType = (Spinner) findViewById(R.id.spinnerVehicleType);
		ArrayAdapter<CharSequence> adapterVehicleType = ArrayAdapter.createFromResource(this, R.array.VehicleType_Values, android.R.layout.simple_spinner_item);
		adapterVehicleType.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		mSpinerVehicleType.setAdapter(adapterVehicleType);
		
		mSpinerSupervisor = (Spinner) findViewById(R.id.spinnerSupervisor);
		mBtnLogin = (Button) findViewById(R.id.btnLogin);
		mBtnLogin.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				
				String userid = mEditUserId.getText().toString();
				String userpwd = mEditPassword.getText().toString();
				if (userid.equals("")) {
					Toast.makeText(RegisterActivity.this, "Please input email address", Toast.LENGTH_SHORT).show();
					return;
				}
				if (userpwd.equals("")) {
					Toast.makeText(RegisterActivity.this, "Please input password", Toast.LENGTH_SHORT).show();
					return;
				}
				if (mEditCompanyName.getText().toString().equals("")) {
					Toast.makeText(RegisterActivity.this, "Please company name", Toast.LENGTH_SHORT).show();
					return;
				}
				if (!isValidCompanyName) {
					Toast.makeText(RegisterActivity.this, "Please correct company name", Toast.LENGTH_SHORT).show();
					return;
				}
				if (mEditUserName.getText().toString().equals("")) {
					Toast.makeText(RegisterActivity.this, "Please user name", Toast.LENGTH_SHORT).show();
					return;
				}
				
				new TaskLoginProcess().execute();
			}
		});
		
		findViewById(R.id.txtForgotPassword).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(RegisterActivity.this, FindPasswordActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0, 0);
			}
		});
		
		findViewById(R.id.txtSignIn).setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0, 0);
			}
		});
		
		mEditCompanyName.setOnFocusChangeListener(new View.OnFocusChangeListener() {
			@Override
			public void onFocusChange(View arg0, boolean arg1) {
				if (arg1 == false) {
					new TaskCheckCompanyName().execute();
				}
				
			}
		});
	}
 
	private class TaskLoginProcess extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(RegisterActivity.this, "", "Login...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(RegisterActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				JSONObject jsonObj = new JSONObject(result);
				if (jsonObj.getInt("code") == 0) {
					
					Toast.makeText(RegisterActivity.this, jsonObj.getString("msg"), Toast.LENGTH_SHORT).show();
					return;
				}
				
				Toast.makeText(RegisterActivity.this, "Sign Up successfully done.", Toast.LENGTH_SHORT).show();
				Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
				startActivity(intent);
				finish();
				overridePendingTransition(0, 0);
				
			} catch (JSONException e) {
				
				e.printStackTrace();
			}
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "signup"; 
			
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpPost httpPost = new HttpPost(url);
				
				ArrayList<NameValuePair> postParameters = new ArrayList<NameValuePair>();
				postParameters.add(new BasicNameValuePair("email", mEditUserId.getText().toString()));
				postParameters.add(new BasicNameValuePair("pwd", mEditPassword.getText().toString()));
				postParameters.add(new BasicNameValuePair("companyname", mEditCompanyName.getText().toString()));
				postParameters.add(new BasicNameValuePair("username", mEditUserName.getText().toString()));
				postParameters.add(new BasicNameValuePair("mobilephone", mEditMobilePhone.getText().toString()));
				postParameters.add(new BasicNameValuePair("homephone", mEditHomePhone.getText().toString()));
				postParameters.add(new BasicNameValuePair("vehiclereg", mEditVehicleReg.getText().toString()));
				postParameters.add(new BasicNameValuePair("userrole", String.valueOf(mSpinerUserRole.getSelectedItem())));
				postParameters.add(new BasicNameValuePair("vehicletype", String.valueOf(mSpinerVehicleType.getSelectedItem())));
				postParameters.add(new BasicNameValuePair("supervisor_id", ListSupervisorIds.get(mSpinerSupervisor.getSelectedItemPosition()) + ""));
				
				httpPost.setEntity(new UrlEncodedFormEntity(postParameters));
				
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                result = httpclient.execute(httpPost, responseHandler);
                Log.e("Debug", url);
			} catch (IOException e) {
				
				e.printStackTrace();
			}
			
			return null;
		}
	}
	
	private class TaskCheckCompanyName extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			isValidCompanyName = false;
			ListSupervisorIds.clear();
			ListSupervisorNames.clear();
			ReplaceSupervisorSpiner();
			loadingDlg = ProgressDialog.show(RegisterActivity.this, "", "Checking Company Name...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(RegisterActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				Log.e("Debug", result);
				JSONObject jsonObj = new JSONObject(result);
				if (jsonObj.getInt("count") == 0) {
					
					Toast.makeText(RegisterActivity.this, jsonObj.getString("msg"), Toast.LENGTH_SHORT).show();
					return;
				}
				isValidCompanyName = true;
				
				// replace the supervisor list
				JSONArray jsonSupList = jsonObj.getJSONArray("suplist");
				for (int i=0; i<jsonSupList.length(); i++) {
					JSONObject jsonSupObj = jsonSupList.getJSONObject(i);
					ListSupervisorIds.add(jsonSupObj.getInt("id"));
					ListSupervisorNames.add(jsonSupObj.getString("name"));
				}
				ReplaceSupervisorSpiner();
				
			} catch (JSONException e) {
				
				e.printStackTrace();
			}
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "checkcompanyName"; 
			
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpPost httpPost = new HttpPost(url);
				
				ArrayList<NameValuePair> postParameters = new ArrayList<NameValuePair>();
				postParameters.add(new BasicNameValuePair("name", mEditCompanyName.getText().toString()));
				httpPost.setEntity(new UrlEncodedFormEntity(postParameters));
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
                result = httpclient.execute(httpPost, responseHandler);
                Log.e("Debug", url);
			} catch (IOException e) {
				
				e.printStackTrace();
			}
			
			return null;
		}
	}
	
	private void ReplaceSupervisorSpiner() {
		String[] nameArr = new String[ListSupervisorNames.size()];
		nameArr = ListSupervisorNames.toArray(nameArr);
		
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this,
	            android.R.layout.simple_spinner_item, nameArr);
		adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		mSpinerSupervisor.setAdapter(adapter);
	}
	public void OnBackPressed() {
		Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
		startActivity(intent);
		finish();
		overridePendingTransition(0, 0);
	}
}
