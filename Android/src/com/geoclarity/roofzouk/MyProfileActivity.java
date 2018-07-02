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
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

@SuppressLint("NewApi")
public class MyProfileActivity extends BaseMenuActivity {

	EditText mEditUserId, mEditPassword, mEditUserName, mEditHomePhone, mEditMobilePhone, mEditVehicleReg, mEditCompanyName;
	Spinner mSpinerUserRole, mSpinerVehicleType;
	Button mBtnLogin;
	SharedPreferences sharedpreferences;
	private int mCurUserId = 0;
	ArrayAdapter<CharSequence> adapterUserRole, adapterVehicleType;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		setContentView(R.layout.activity_myprofile);
		
		//getActionBar().setTitle("MyProfile"); 
		
		SharedPreferences sharedpreferences = getSharedPreferences(Globals.PREFERENCE_NAME, Context.MODE_PRIVATE);
		mCurUserId = sharedpreferences.getInt("userIdxKey", 0);
		
		mEditUserId = (EditText) findViewById(R.id.editUserId);
		mEditPassword = (EditText) findViewById(R.id.editPassword);
		mEditCompanyName = (EditText) findViewById(R.id.editCompanyName);
		mEditHomePhone = (EditText) findViewById(R.id.editHomePhone);
		mEditMobilePhone = (EditText) findViewById(R.id.editMobilePhone);
		mEditVehicleReg = (EditText) findViewById(R.id.editVehicleReg);
		mEditUserName = (EditText) findViewById(R.id.editUserName);
		
		mSpinerUserRole = (Spinner)findViewById(R.id.spinnerUserRole);
		adapterUserRole = ArrayAdapter.createFromResource(this, R.array.UserRole_Values, android.R.layout.simple_spinner_item);
		adapterUserRole.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		mSpinerUserRole.setAdapter(adapterUserRole);
		
		mSpinerVehicleType = (Spinner) findViewById(R.id.spinnerVehicleType);
		adapterVehicleType = ArrayAdapter.createFromResource(this, R.array.VehicleType_Values, android.R.layout.simple_spinner_item);
		adapterVehicleType.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		mSpinerVehicleType.setAdapter(adapterVehicleType);
		
		mBtnLogin = (Button) findViewById(R.id.btnLogin);
		mBtnLogin.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				
				String userid = mEditUserId.getText().toString();
				if (userid.equals("")) {
					Toast.makeText(MyProfileActivity.this, "Please input email address", Toast.LENGTH_SHORT).show();
					return;
				}
				
				if (mEditCompanyName.getText().toString().equals("")) {
					Toast.makeText(MyProfileActivity.this, "Please company name", Toast.LENGTH_SHORT).show();
					return;
				}
				
				if (mEditUserName.getText().toString().equals("")) {
					Toast.makeText(MyProfileActivity.this, "Please user name", Toast.LENGTH_SHORT).show();
					return;
				}
				
				new TaskLoginProcess().execute();
			}
		});
		
		(new TaskGetProfileInfo()).execute();
	}
 
	private class TaskLoginProcess extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(MyProfileActivity.this, "", "Updating...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(MyProfileActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			
			Toast.makeText(MyProfileActivity.this, "Updating successfully done.", Toast.LENGTH_SHORT).show();
			return;
		
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "modifyprofile/" + mCurUserId; 
			
			HttpClient httpclient = new DefaultHttpClient();
			try {
				HttpPost httpPost = new HttpPost(url);
				
				ArrayList<NameValuePair> postParameters = new ArrayList<NameValuePair>();
				postParameters.add(new BasicNameValuePair("email", mEditUserId.getText().toString()));
				postParameters.add(new BasicNameValuePair("pwd", mEditPassword.getText().toString()));
				postParameters.add(new BasicNameValuePair("username", mEditUserName.getText().toString()));
				postParameters.add(new BasicNameValuePair("mobilephone", mEditMobilePhone.getText().toString()));
				postParameters.add(new BasicNameValuePair("homephone", mEditHomePhone.getText().toString()));
				postParameters.add(new BasicNameValuePair("vehiclereg", mEditVehicleReg.getText().toString()));
				postParameters.add(new BasicNameValuePair("userrole", String.valueOf(mSpinerUserRole.getSelectedItem())));
				postParameters.add(new BasicNameValuePair("vehicletype", String.valueOf(mSpinerVehicleType.getSelectedItem())));
				
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
	
	private class TaskGetProfileInfo extends AsyncTask<Void, Void, Void> {
		ProgressDialog loadingDlg;
		String result = null; 
		public void onPreExecute() {
			result = null;
			loadingDlg = ProgressDialog.show(MyProfileActivity.this, "", "Loading profile info...", true, false);
		}
		public void onPostExecute(Void rst) {
			
			loadingDlg.dismiss();
			
			if (result == null) {
				Toast.makeText(MyProfileActivity.this, "There is a error in server connection", Toast.LENGTH_SHORT).show();
				return;
			}
			try {
				Log.e("Debug", result);
				JSONObject jsonObj = new JSONObject(result);
				
				mEditUserName.setText(jsonObj.getString("username"));
				mEditCompanyName.setText(jsonObj.getString("company_name"));
				mEditHomePhone.setText(jsonObj.getString("mobilephone"));
				mEditMobilePhone.setText(jsonObj.getString("homephone"));
				mEditVehicleReg.setText(jsonObj.getString("vehicle_reg"));
				mEditUserId.setText(jsonObj.getString("email"));
				
				String mStrRole = jsonObj.getString("user_roles");
				String mStrVehicleType = jsonObj.getString("vehicletype");
				for (int i=0; i<adapterUserRole.getCount(); i++) {
					if (adapterUserRole.getItem(i).equals(mStrRole)) {
						mSpinerUserRole.setSelection(i);
						break;
					}
				}
				for (int i=0; i< adapterVehicleType.getCount(); i++) {
					if (adapterVehicleType.getItem(i).equals(mStrVehicleType)) {
						mSpinerVehicleType.setSelection(i);
						break;
					}
				}
			} catch (JSONException e) {
				
				e.printStackTrace();
			}
		}
		@Override
		protected Void doInBackground(Void... params) {
			
			String url = Globals.ApiPath + "myprofile/" + mCurUserId; 
			
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
