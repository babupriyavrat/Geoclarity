<?php
class Api extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('url');
		
		$this->load->model('member');
		$this->load->model('task');
		$this->load->model('user');
		/*
		$this->load->model('group');
		$this->load->model('theme');
		$this->load->model('question');
		*/
	}
	
	public function login() {
		
		$ret = $this->user->loginUser($_REQUEST['email'], $_REQUEST['pwd']);
		if ($ret!=false) {
			$msg = "";
			$useridx = $ret->user_id;	

			// check if lat and long variables
			if (isset($_REQUEST['userlat']) && isset($_REQUEST['userlong'])) {
				$lat = $_REQUEST['userlat'];
				$long = $_REQUEST['userlong'];
				$this->user->addUserLocationByLogin($useridx, $lat, $long);
			}
			
			// check if there is gcm registering id
			if (isset($_REQUEST['gcmid'])) {
				$this->user->setUserGcmKey($useridx, $_REQUEST['gcmid']);
			}
 		} else {
 			$msg = "Email or Password is incorrect";
 			$useridx = 0; 
 		}
 		
		$result = array(
			"msg" => $msg, 
			"userid" => $useridx
		);
		echo json_encode($result);
	}
	
	public function findpwd() {
		$ret = $this->user->getUserInfoByEmail($_REQUEST['email']);
		if ($ret!= false) {
			
			$resetPwd = "123";
			
			$mailTitle = "[Geoclarity] Forgot your password.";
			$mailContent = "Dear ".$ret->username."<br/>";
			$mailContent .= "Your password was changed to {$resetPwd}.<br/><br/> ";
			
			$headers = "Content-type: text/html; charset=UTF-8\r\n" . "From: info@geoclarity.com";
			mail($ret->email, $mailTitle, $mailContent, $headers);
			$success = 1;
			
			$this->user->setUserPassword($ret->user_id, $resetPwd);
		} else {
			$success = 0;
		}
		$result = array(
			'code' => $success
		);
		echo json_encode($result);
	}
	
	public function signup() {
		
		// 1. check company name 
		$companyName = $this->db->escape_str($_REQUEST['companyname']);
		$company_id = $this->member->checkCompanyName($companyName);
		$email = $this->db->escape_str($_REQUEST["email"]);
		$username = $this->db->escape_str($_REQUEST["username"]);
		$pwd = $this->db->escape_str($_REQUEST["pwd"]);
		$mobilephone = $this->db->escape_str($_REQUEST["mobilephone"]);
		$homephone = $this->db->escape_str($_REQUEST["homephone"]);
		$vehiclereg = $this->db->escape_str($_REQUEST["vehiclereg"]);
		$vehicletype = $this->db->escape_str($_REQUEST["vehicletype"]);
		$userrole = $this->db->escape_str($_REQUEST['userrole']);
		$supervisor_id = $this->db->escape_str($_REQUEST['supervisor_id']);
		$code = 0;
		if ($company_id == 0) {
			$msg = "Cannot find the company information";
		} else {
			// check email is registered 
			$ret = $this->user->getUserInfoByEmail($_REQUEST['email']);
			if ($ret!= false) {
				$msg = "This email was already registered.";
			} else {
				$user_id = $this->user->addUserInfo($supervisor_id, $company_id, $username, $email, $pwd, $homephone, $mobilephone, $userrole, $vehicletype, $vehiclereg);
				
				$code = $user_id;
			}
		}
		
		$result = array(
			"msg" => $msg, 
			"code" => $code
		);
		echo json_encode($result);
	}
	
	public function insertlocation() {
		$user_id = $_REQUEST['userid'];
		$lat = $_REQUEST['lat'];
		$long = $_REQUEST['long'];
		$battery = $_REQUEST['battery'];
		
		$this->user->addUserLocation($user_id, $lat, $long, $battery);
	}
	public function getCurrentTask() {
		$user_id = $_REQUEST['userid'];
		// find if "INPROGRESS" task 
		$inpTask = $this->task->FindUserProcessTask($user_id);
		if ($inpTask==null) {
			$inpTask = $this->task->FindUserCreatedTask($user_id);
		} 
		// find user lat and long 
		$userInfo = $this->user->getUserInfoById($user_id);
		$task_id = 0; $task_detail = "";
		
		if ($inpTask == null) {
			$result = array(
				"taskid" => $task_id, 
				"taskdesc" => $task_detail, 
				"userloclat" => $userInfo->last_latitude, 
				"userloclong" => $userInfo->last_longitude
			);	
		} else {
			$task_id = $inpTask->task_id;
			
			$task_detail = "Customer Name: ".$inpTask->contact_name."\n"
					."Customer Address: ".$inpTask->contact_address."\n"
					."Customer Phone: ".$inpTask->contact_phone."\n"
					."Task Category: ".$inpTask->TasktypeCategory."\n"
					."Appoint Start Time: ".$inpTask->scheduled_Start."\n"
					."Appoint End Time: ".$inpTask->scheduled_End."\n"
					."Post Code: ".$inpTask->postcode."\n"
					."Current Status: ".$inpTask->task_Status."\n"
					.$inpTask->notes
				;
			
			
			$result = array(
				"taskid" => $task_id, 
				"taskdesc" => $task_detail,
				"taskloclat" => $inpTask->task_lat, 
				"taskloclong" => $inpTask->task_long,
				"userloclat" => $userInfo->last_latitude, 
				"userloclong" => $userInfo->last_longitude, 
				"phone" => $inpTask->contact_phone
			);
			
			// update the actual_start field 
			$this->task->ActualStartTask($inpTask->task_id);
		}
		echo json_encode($result);
				
	}
	public function completetask() {
		$task_id = $_REQUEST['taskid'];
		$this->task->CompleteTask($task_id);
	}
	
	public function canceltask() {
		$task_id = $_REQUEST['taskid'];
		$this->task->CancelTask($task_id);
	}
	
	public function checkCompanyName() {
		
		$name = $this->db->escape_str($_REQUEST['name']);
		$company_id = $this->member->checkCompanyName($name);
		if ($company_id == 0) {
			$result = array(
				'company_id' => $company_id,
				'count' => 0, 
				'msg' => "There is no company information."
			);
			echo json_encode($result);	
		} else {
			$supList = $this->member->searchSupervisors("", $company_id);
			if (count($supList) == 0) {
				$result = array(
					'company_id' => $company_id,
					'count' => 0, 
					'msg' => "There is no supervisors in this company."
				);
				echo json_encode($result);	
			} else {
				$newSupList = array();
				foreach ($supList as $supInfo) {
					$newSupList[] = array(
						'id' => $supInfo->supervisor_id, 
						'name' => $supInfo->supervisor_name
					);
				}
				$result = array(
					'company_id' => $company_id,
					'count' => count($supList),  
					'suplist' => $newSupList
				);
				echo json_encode($result);
			}
		}
	}
	
	public function sostask() {
		$task_id = $_REQUEST['taskid'];
		$user_id = $_REQUEST['userid'];
		$curUserInfo = $this->user->getUserInfoById($user_id);
		
		// set task to SOS 
		$sosmain_id = $this->user->setTaskSOS($task_id, $user_id);
		if ($sosmain_id == 0) return;
		
		// find another users belong to this company of task
		$anotherUsers = $this->user->findAnotherUsersOfCompany($task_id, $user_id);
		
		$userGcmIds = array();
		foreach ($anotherUsers as $userInfo) {
			$userGcmIds[] = $userInfo->gcmid;
		}
		$data = array(
			"msg" => "New SOS Task from ".$curUserInfo->username, 
			"sosmain_id" => $sosmain_id, 
			"type" => 1 //   
		);		
		$this->sendPush($data, $userGcmIds);
	}
	
	public function getSosTask() {
		$sosmain_id = $_REQUEST['sosmain_id'];
		$user_id = $_REQUEST['user_id'];
		$taskInfo = $this->task->GetSOSTask($sosmain_id);
		
		$task_detail = "Customer Name: ".$taskInfo->contact_name."\n"
					."Customer Address: ".$taskInfo->contact_address."\n"
					."Customer Phone: ".$taskInfo->contact_phone."\n"
					."Task Category: ".$taskInfo->TasktypeCategory."\n"
					."Appoint Start Time: ".$taskInfo->scheduled_Start."\n"
					."Appoint End Time: ".$taskInfo->scheduled_End."\n"
					."Post Code: ".$taskInfo->postcode."\n"
					."Current Status: ".$taskInfo->task_Status."\n"
					.$taskInfo->notes
				;
				
		if ($taskInfo->sos_status > 0) {
			$task_detail = $task_detail."\n\nThis SOS task was already processed.";
			if ($taskInfo->sos_status == 1) {
				$assignedUserInfo = $this->member->getUserInfoById($taskInfo->user_id);
				$task_detail .= "\nAssigned User: ".$assignedUserInfo->email;
			}
			$result = array(
				"taskid" => 0, 
				"taskdesc" =>  $task_detail
			);
				
		} else {
			// find user lat and long 
			$userInfo = $this->user->getUserInfoById($user_id);
			$result = array(
				"taskid" => $taskInfo->task_id, 
				"taskdesc" => $task_detail,
				"taskloclat" => $taskInfo->task_lat, 
				"taskloclong" => $taskInfo->task_long,
				"userloclat" => $userInfo->last_latitude, 
				"userloclong" => $userInfo->last_longitude
			);
		}
		echo json_encode($result);
	}
	
	public function sosTaskResponse() {
		$sosmain_id = $_REQUEST['sosmain_id'];
		$user_id = $_REQUEST['user_id'];
		$rescode = $_REQUEST['rescode'];
		
		$userInfo = $this->user->getUserInfoById($user_id);
		
		$userAddr = "";
		// find user location 
		if ($userInfo->last_latitude!="") {
			$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$userInfo->last_latitude},{$userInfo->last_longitude}&key=AIzaSyAbLgfQr6-ils7tHMyDkrmTC6HX8PCzSwU";
	        $apiResult = file_get_contents($url);
			$apiJson = json_decode($apiResult);
			$userAddr = $apiJson->results[0]->formatted_address;
		}
		$this->user->setSOSResponse($sosmain_id, $user_id, $rescode, $userAddr);
	}
	public function sendPush($data, $regid) {
		$apiKey = "AIzaSyAbLgfQr6-ils7tHMyDkrmTC6HX8PCzSwU";
		
		// Replace with real client registration IDs 
		$registrationIDs = array( "APA91bFgFqdgXG8TkiNmnDT91dhOxFA1yxas2ppOw3Sll9w8R0WyD_g_la1Ti1vCXT2XVdVMV7zE3ileGdCwI3HDvbZHY-rToHrobXJElCBQdYPJMA3aXTrl5if9WpMkpDFVrF5tCuHr");
		//$registrationIDs = array($regid);
		if ($regid=='') $regid = $registrationIDs;
		// Message to be sent
		$message = "x";
		
		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';
		if ($data == '') {
		$data = array ( 
					"msg" => "test test", 
					"sosmain_id" => 11
		    	);
		}
		$fields = array(
		                'registration_ids'  => $regid,
		                'data'              => $data, 
		                );
		
		$headers = array( 
		                    'Authorization: key=' . $apiKey,
		                    'Content-Type: application/json'
		                );
		
		// Open connection
		$ch = curl_init();
		
		// Set the url, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $url );
		
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
		
		// Execute post
		$result = curl_exec($ch);
		//var_dump($result);
		// Close connection
		curl_close($ch);
	}
	
	public function SosSchedule() {
		$sosmainList = $this->task->getCurrentSOSTasksAll();
		foreach ($sosmainList as $sosmainInfo) {
			$resList = $this->task->getSosResponseList($sosmainInfo->sosmain_id);
			$isAssiged = false;
			
			// find original user info 
			$originalUserInfo = $this->member->getUserInfoById($sosmainInfo->original_user);
			
			foreach ($resList as $resInfo) {
				if ($resInfo->response == 1) {
					// assign this task to user
					
					$this->task->assisnSosTask($sosmainInfo->sosmain_id, $sosmainInfo->task_id, $resInfo->user_id);
					// find assigned user info
					$assignedUserInfo = $this->member->getUserInfoById($resInfo->user_id);
					$userGcmIds = array($assignedUserInfo->gcmid);
					
					$data = array(
						"msg" => "You are assigned a new sos task", 
						"sosmain_id" => $sosmainInfo->sosmain_id, 
						"type" => 2 //   
					);
					$this->sendPush($data, $userGcmIds);
					
					$isAssiged = true; 
					
					break;
				}
			} 
			
			if (!$isAssiged) {
				$this->task->cancelSosTask($sosmainInfo->sosmain_id, $sosmainInfo->task_id);
			}
			
			// send push message that task is processed
			$userGcmIds = array($originalUserInfo->gcmid);
			
			$data = array(
				"msg" => "Your sos task scheduling is finished", 
				"sosmain_id" => $sosmainInfo->sosmain_id, 
				"type" => 3 //   
			);		
			$this->sendPush($data, $userGcmIds);
		}
	}
	
	public function myprofile($user_id) {
		$userInfo = $this->user->getUserInfoById($user_id);
		$companyInfo = $this->member->getCompanyInfoById($userInfo->company_id);
		$userInfo->company_name = $companyInfo->company_name;
		echo json_encode($userInfo);
	}
	
	public function modifyprofile($user_id) {
		$email = $this->db->escape_str($_REQUEST["email"]);
		$username = $this->db->escape_str($_REQUEST["username"]);
		$pwd = $this->db->escape_str($_REQUEST["pwd"]);
		$mobilephone = $this->db->escape_str($_REQUEST["mobilephone"]);
		$homephone = $this->db->escape_str($_REQUEST["homephone"]);
		$vehiclereg = $this->db->escape_str($_REQUEST["vehiclereg"]);
		$vehicletype = $this->db->escape_str($_REQUEST["vehicletype"]);
		$userrole = $this->db->escape_str($_REQUEST['userrole']);
		
		$this->user->modifyUserInfoById($user_id, $username, $email, $pwd, $homephone, $mobilephone, $userrole, $vehicletype, $vehicle_reg);
	}
	
	public function taskhistory($user_id) {
		$taskList = $this->task->getUserTaskHistory($user_id);
		$retArray = array();
		foreach ($taskList as $taskInfo) {
			$task_detail = "Customer Name: ".$taskInfo->contact_name."\n"
					
					."Task Category: ".$taskInfo->TasktypeCategory."\n"
					."Appoint Start Time: ".$taskInfo->scheduled_Start."\n"
					."Appoint End Time: ".$taskInfo->scheduled_End."\n"
					."Post Code: ".$taskInfo->postcode."\n"
					."Current Status: ".$taskInfo->task_Status."\n"
					.$taskInfo->notes
				;
			$retArray[] = $task_detail;
		}
		echo json_encode($retArray);
	}
}