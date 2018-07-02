<?php
class Company extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('url');
		
		$this->load->model('member');
		$this->load->model('task');
		/*
		$this->load->model('group');
		$this->load->model('theme');
		$this->load->model('question');
		*/
	}
	public function index()
	{
		if (!$this->checkLogin()) {
	 		redirect(  'company/login', 'refresh');	  
	 	} else {
	 		redirect(  'company/home', 'refresh');
	 	}
	}
	function checkLogin() {
		 $company = $this->company = $this->session->userdata('company');
		 if ($this->company) {
		 	return true;
		 } else {
		 	return false;
		 }
	}
	public function login() {
		// check https 
	
		if($_SERVER['SERVER_PORT'] !== 443 &&
		   (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
		  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		  exit;
		}
		
		$reqArray = array();
		$reqArray['registerError'] = "";
		$reqArray['registerSuccess'] = "";
		$reqArray['loginError'] = "";
		
		$task = $this->input->post('task');
		
		
		if ($task == "login") {
			$ret = $this->member->loginCompany($_REQUEST['email'], $_REQUEST['pwd']);
			if ($ret!=false) {
				if ($ret->verifyemail == 0) {
					$reqArray['loginError'] = "Please verify your email address.";
				} else {
		 			$this->session->set_userdata('company', $ret);
		 			redirect(  'company/home', 'refresh');
				}
	 		} else {
	 			$reqArray['loginError'] = "Email or Password is incorrect.";
	 		}
		} else if ($task == "register") {
			$reqArray['companyname'] = $this->db->escape_str($_REQUEST['companyname']);
			$reqArray['email'] = $this->db->escape_str($_REQUEST['email']);
			$reqArray['pwd'] = $this->db->escape_str($_REQUEST['pwd']);
			$reqArray['address'] = $this->db->escape_str($_REQUEST['address']);
			$reqArray['mobile'] = $this->db->escape_str($_REQUEST['mobile']);
			$reqArray['trial_limit_user'] = $this->db->escape_str($_REQUEST['trial_limit_user']);
			$reqArray['trial_limit_sup'] = $this->db->escape_str($_REQUEST['trial_limit_sup']);
			$reqArray['timezone'] = $this->db->escape_str($_REQUEST['timezone']);
			$result = $this->member->registerCompany($reqArray);
			
			if ($result == "") {
				$reqArray['registerSuccess'] = "Thank you using our service.<br/>A verification email is sent to your email.<br/>Please verify your email address.";	
			} else {
				$reqArray['registerError'] = $result;
			}
		}
		$this->load->view('company/login', $reqArray);
	}
	
	public function signup() {
		// check https 
	
		if($_SERVER['SERVER_PORT'] !== 443 &&
		   (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
		  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		  exit;
		}
		
		$reqArray = array();
		$reqArray['registerError'] = "";
		$reqArray['registerSuccess'] = "";
		
		$task = $this->input->post('task');
		
		if ($task == "register") {
			$reqArray['companyname'] = $this->db->escape_str($_REQUEST['companyname']);
			$reqArray['email'] = $this->db->escape_str($_REQUEST['email']);
			$reqArray['pwd'] = $this->db->escape_str($_REQUEST['pwd']);
			$reqArray['address'] = $this->db->escape_str($_REQUEST['address']);
			$reqArray['mobile'] = $this->db->escape_str($_REQUEST['mobile']);
			$reqArray['trial_limit_user'] = $this->db->escape_str($_REQUEST['trial_limit_user']);
			$reqArray['trial_limit_sup'] = $this->db->escape_str($_REQUEST['trial_limit_sup']);
			$reqArray['timezone'] = $this->db->escape_str($_REQUEST['timezone']);
			$result = $this->member->registerCompany($reqArray);
			
			if ($result == "") {
				$reqArray['registerSuccess'] = "Thank you using our service.<br/>A verification email is sent to your email.<br/>Please verify your email address.";	
			} else {
				$reqArray['registerError'] = $result;
			}
		}
		$this->load->view('company/signup', $reqArray);
	}
	
	public function forgotpassword() {
		$reqArray = array();
		$reqArray['loginError'] = "";
		$reqArray['msgSuccess'] = "";
		$task = $this->input->post('task');
		if ($task == "login") {
			$ret = $this->member->getCompanyInfoByEmail($_REQUEST['email']);
			if ($ret!=null) {
				// reset password and send a email 
				$resetPwd = "123";
				$mailTitle = "[Geoclarity] Forgot your password.";
				$mailContent = "Dear ".$ret->company_name."<br/>";
				$mailContent .= "Your password was changed to {$resetPwd}.<br/><br/> ";
				
				$headers = "Content-type: text/html; charset=UTF-8\r\n" . "From: info@geoclarity.com";
				mail($ret->company_email, $mailTitle, $mailContent, $headers);
				
				$this->member->setCompanyPassword($ret->company_id, $resetPwd);
				$reqArray['msgSuccess'] = "Success sent a email to your email address.";
	 		} else {
	 			$reqArray['loginError'] = "Cannot find supervisor information.";
	 		}
		} 
		$this->load->view('company/findpwd', $reqArray);
	}
	
	public function checkCompanyName() {
		$companyName = $this->db->escape_str($_REQUEST['companyname']);
		$isAvailable = true; $msg = "";
		if ($this->member->checkCompanyName($companyName)> 0) {
			$isAvailable = false;
			$msg = "This company name already registered.";
		} else {
		}
		echo json_encode(array(
		    'valid' => $isAvailable,
			'message' => $msg,
		));
	}
	public function verifyemail() {
		$reqArray['verifySuccess'] = "";
		$reqArray['verifyError'] = "";
		
		$token = $_REQUEST['token'];
		$token = $this->db->escape_str($token);
		$ret = $this->member->verifyCompanyEmail($token);
		if ($ret!=false) {
			if ($ret->verifyemail == 1) {
				$reqArray['verifyError'] = "You already verified your email address";
			} else {
				$this->member->updateVerifyCompanyEmail($token);
	 			$reqArray['verifySuccess'] = "Successfully verified your email address";
			}
 		} else {
 			$reqArray['verifyError'] = "Invalid access.";
 		}
		$this->load->view('company/verifyemail', $reqArray);
	}
	public function logout() {
		$this->session->unset_userdata('company');
		$this->session->sess_destroy();
		redirect(  'company/login', 'refresh');
	} 
	public function home() {
		if (!$this->checkLogin()) redirect(  'company/login', 'refresh');	  
		$this->load->view('company/header');
		
		$reqArray['seltype'] = 0;
		
		$where = "";
		if (isset($_REQUEST['seltype'])) {
			$seltype = intval($_REQUEST['seltype']);
			//$seltypeList = implode(",", $seltypes);
			//foreach ($seltypeList as $seltype) {
				if ($seltype == 1) {
					$where .= " task_Status = 'DELAYED' ";
				} else if ($seltype == 2) {
					$where .= " task_Status = 'COMPLETED' ";
				} else if ($seltype == 3) {
					$where .= " SOS_STATUS = 'Yes' ";
				} else if ($seltype == 4) {
					$where .= " task_Status = 'CANCELLED' ";
				}
			//}
			$reqArray['seltype'] = $seltype; 
		}

		// find top 10 Supervisors 
		$reqArray['top10Sup'] = $this->member->getTopSupervisors($where, $_SESSION['company']->company_id); 
		$reqArray['top10SupCount'] = count($reqArray['top10Sup']);
		
		$tasks = $this->task->getAllTasks($where, $_SESSION['company']->company_id);
		$jsonTasks = json_encode($tasks);
		$reqArray['jsonTasks'] = $jsonTasks;
		
		$this->load->view('company/main', $reqArray);
	}
	
	public function profile() {
		$reqArray = array();
		if (isset($_REQUEST['name'])) {
			$_SESSION['company']->company_name = $this->db->escape_str($_REQUEST['name']);
			$_SESSION['company']->company_address = $this->db->escape_str($_REQUEST['address']);
			$_SESSION['company']->company_phone = $this->db->escape_str($_REQUEST['phone']);
			$_SESSION['company']->timezone = $this->db->escape_str($_REQUEST['timezone']);
			$pwd =  $this->db->escape_str($_REQUEST['pwd']);
			if ($_FILES['logo']['size'] > 0) {
				$destpath = FCPATH."datas/logo/".$_FILES['logo']['name'];
				move_uploaded_file($_FILES['logo']['tmp_name'], $destpath);
				$_SESSION['company']->profileimage = $_FILES['logo']['name'];
			} 
			
			$this->member->modifyCompany($_SESSION['company']->company_id, $_SESSION['company']->company_name, $_SESSION['company']->company_address, $_SESSION['company']->company_phone, $_SESSION['company']->profileimage, $_SESSION['company']->timezone, $pwd);
		}
		
		$this->load->view('company/header');
		$this->load->view('company/profile', $reqArray);
	}
	
	public function upload() {
		$reqArray = array();
		$reqArray['taskSuccess'] = "";
		$reqArray['taskFailed'] = "";
		$reqArray['supSuccess'] = "";
		$reqArray['supFailed'] = "";
		$reqArray['userSuccess'] = "";
		$reqArray['userFailed'] = "";
		
		$task = $this->input->post('task');
		if ($task == "tasks") {
			include_once 'application/libraries/PHPExcel/PHPExcel.php';
			try {
				$inputFileName = $_FILES['file']['tmp_name']."";
				/**  Identify the type of $inputFileName  **/
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				/**  Create a new Reader of the type that has been identified  **/
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				//$objReader->setReadFilter( new MyReadFilter() );
				/**  Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				$reqArray['taskFailed'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
			}
			if ($reqArray['taskFailed'] == "") {
				$importCnt = 0;
				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
	
				$itemList = array();
				for ($row = 1; $row <= $highestRow; $row++){ 
					try {
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		            } catch(Exception $e){
		            	continue;
		            }
		            
		            $info = $rowData[0];
		            if ($info[0] == "" || $info[1] == "" || $info[2] == "" || $info[3] == ""|| $info[4] == ""|| $info[5] == "") continue;
		            /*
		            $startCell = $sheet->getCell('G'.$row);
		            $startCellFormattedValue = trim($startCell->getFormattedValue());
					if (!empty($startCellFormattedValue)) {
					    $dateInTimestampValue = PHPExcel_Shared_Date::ExcelToPHP($startCell->getValue());
					    $dateInTimestampValue=$dateInTimestampValue;
					     $info[6] = date("Y-m-d H:i", $dateInTimestampValue); 
					}
					$endCell = $sheet->getCell('H'.$row);
		            $endCellFormattedValue = trim($endCell->getFormattedValue());
					if (!empty($endCellFormattedValue)) {
					    $dateInTimestampValue = PHPExcel_Shared_Date::ExcelToPHP($endCell->getValue());
					    $dateInTimestampValue=$dateInTimestampValue ;
					     $info[7] = date("Y-m-d H:i", $dateInTimestampValue); 
					}*/
		            $info[6] = date("Y-m-d H:i", strtotime($info[6]));
		            $info[7] = date("Y-m-d H:i", strtotime($info[7]));
		            // check user
		            $user_id = $this->member->FindCompanyUserByEmail($info[5]);
		            if ($user_id == 0) continue;
		            $info[5] = $user_id;
					
		            // check contact 
		            $contactInfo = $this->member->FindContact($_SESSION['company']->company_id, $info[1], $info[2], $info[3], $info[4]);
		            if ($contactInfo == NULL) {
		            	// find lat and long information
		            	
		            	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$info[2]." ".$info[3]."&key=AIzaSyAbLgfQr6-ils7tHMyDkrmTC6HX8PCzSwU";
		            	$url = str_replace(" ", "+", $url);
		            	$apiResult = file_get_contents($url);
		            	$apiJson = json_decode($apiResult);
		            	$lat = $apiJson->results[0]->geometry->location->lat;
		            	$long = $apiJson->results[0]->geometry->location->lng;
		            	
		            	$this->member->InsertContact($_SESSION['company']->company_id, $info[1], $info[2], $info[3], $info[4], $lat, $long);
		            } else {
		            	$lat = $contactInfo->contact_lat;
		            	$long = $contactInfo->contact_lng;
		            }
		            
		            if ( $this->task->importTaskByCompany($info, $_SESSION['company']->company_id, $lat, $long) > 0) {
		            	$importCnt++;
		            }
				}
				//break;
			}
			$reqArray['taskSuccess'] = "Successfully imported {$importCnt} tasks.";
		} else if ($task == "supervisors") {
			include_once 'application/libraries/PHPExcel/PHPExcel.php';
			try {
				$inputFileName = $_FILES['file']['tmp_name']."";
				/**  Identify the type of $inputFileName  **/
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				/**  Create a new Reader of the type that has been identified  **/
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				//$objReader->setReadFilter( new MyReadFilter() );
				/**  Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				$reqArray['supFailed'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
			}
			if ($reqArray['supFailed'] == "") {
				$importCnt = 0;
				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
	
				$itemList = array();
				for ($row = 1; $row <= $highestRow; $row++){ 
					try {
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		            } catch(Exception $e){
		            	continue;
		            }
		            
		            $info = $rowData[0];
		            if ($info[0] == "" || $info[1] == "" || $info[2] == "" || $info[3] == ""|| $info[4] == "") continue;
		            
		            if ( $this->member->importSupervisorByCompany($info, $_SESSION['company']->company_id )> 0) {
		            	$importCnt++;
		            }
				}
				//break;
			}
			$reqArray['supSuccess'] = "Successfully imported {$importCnt} Supervisor.";
			
			
		} else if ($task == "users") {
			include_once 'application/libraries/PHPExcel/PHPExcel.php';
			try {
				$inputFileName = $_FILES['file']['tmp_name']."";
				/**  Identify the type of $inputFileName  **/
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				/**  Create a new Reader of the type that has been identified  **/
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				//$objReader->setReadFilter( new MyReadFilter() );
				/**  Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				$reqArray['userFailed'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
			}
			if ($reqArray['userFailed'] == "") {
				$importCnt = 0;
				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
	
				$itemList = array();
				for ($row = 1; $row <= $highestRow; $row++){ 
					try {
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		            } catch(Exception $e){
		            	continue;
		            }
		            
		            $info = $rowData[0];
		            if ($info[0] == "" || $info[1] == "" || $info[2] == "" || $info[3] == ""|| $info[4] == "") continue;
		            
		            if ( $this->member->importUserByCompany($info, $_SESSION['company']->company_id > 0)) {
		            	$importCnt++;
		            }
				}
				//break;
			}
			$reqArray['userSuccess'] = "Successfully imported {$importCnt} Users.";
		}
		
		$this->load->view('company/header');
		$this->load->view('company/upload', $reqArray);
	}
	public function search() {
		$reqArray = array();
		$search = $this->db->escape_str($_REQUEST['q']);
		
		$reqArray['ContactList'] = $this->member->searchContacts($search, $_SESSION['company']->company_id);
		$reqArray['TaskList'] = $this->task->searchTasks($search, $_SESSION['company']->company_id);
		$reqArray['UserList'] = $this->member->searchUsers($search, $_SESSION['company']->company_id);
		$reqArray['SupList'] = $this->member->searchSupervisors($search, $_SESSION['company']->company_id);
		
		$this->load->view('company/header');
		$this->load->view('company/search', $reqArray);
	}
	
	public function getTasksCountByMap() {
		
		$lat1= $_REQUEST['lat1'];
		$lat2= $_REQUEST['lat2'];
		$long1= $_REQUEST['long1'];
		$long2= $_REQUEST['long2'];
		
		$where = "";
		$seltype = intval($_REQUEST['seltype']);
		if ($seltype == 1) {
			$where .= " task_Status = 'DELAYED' ";
		} else if ($seltype == 2) {
			$where .= " task_Status = 'COMPLETED' ";
		} else if ($seltype == 3) {
			$where .= " SOS_STATUS = 'Yes' ";
		} else if ($seltype == 4) {
			$where .= " task_Status = 'CANCELLED' ";
		}
			//}
			
		$retVal = array();
		$retVal['Created'] = $this->task->getTaskCountByMap('CREATED', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['Completed'] = $this->task->getTaskCountByMap('COMPLETED', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['Cancelled'] = $this->task->getTaskCountByMap('CANCELLED', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['Delayed'] = $this->task->getTaskCountByMap('DELAYED', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['Sos'] = $this->task->getTaskSOSCountByMap($lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['Progress'] = $this->task->getTaskCountByMap('INPROGRESS', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['PendScheduled'] = $this->task->getTaskCountByMap('PENDSCHEDULE', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		$retVal['ReScheduled'] = $this->task->getTaskCountByMap('RESCHEDULED', $lat1, $long1, $lat2, $long2, $where, $_SESSION['company']->company_id);
		echo json_encode($retVal);
		exit();	
	}
	
	
	public function getRegions() {
		$search = $this->db->escape_str($_REQUEST['region']);
		$list = $this->task->searchRegionsTasks($search, $_SESSION['company']->company_id);
		echo json_encode($list);
		exit();
	}
	
	public function Region() {
		$reqArray = array();
		$postcode = $this->db->escape_str($_REQUEST['postcode']);
		$reqArray['postcode'] = $postcode;
		$reqArray['region'] = $this->task->getRegionNameByPostcode($postcode);
		
		$where = "postcode = '{$postcode}'";
		$tasks = $this->task->getAllTasks($where, $_SESSION['company']->company_id);

		$jsonTasks = json_encode($tasks);
		$reqArray['jsonTasks'] = $jsonTasks;
		
		$reqArray['supList'] = $this->task->getSupListByPostcode($postcode, $_SESSION['company']->company_id);
		//print_r($reqArray['supList']);
		$this->load->view('company/header');
		$this->load->view('company/region', $reqArray);
	}
	
	public function getSups() {
		$search = $this->db->escape_str($_REQUEST['search']);
		$list = $this->member->searchSupervisors($search, $_SESSION['company']->company_id);
		echo json_encode($list);
		exit();
	}
	
	public function godashboard($sup_id) {
		$supInfo = $this->member->getSupervisorInfoById($sup_id);
		$this->session->set_userdata('fromcompany', true);
		$this->session->set_userdata('supervisor', $supInfo);
		header('location: /roofzouk/index.php/supervisor/home');
	}
}