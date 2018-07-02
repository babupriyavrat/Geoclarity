<?php
Class User extends CI_Model{
	
	function __construct(){
	   	parent::__construct();
		$this->load->helper('date');
	}
	
	
	function loginUser($email, $pwd) {
		// check email is already registerd 
		$pwd = hash("sha512", $pwd);
		$sql = "select * from user where email='{$email}' and user_password='{$pwd}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $result[0];
		}
		else{
			return false;
		}
	}
	function getUserInfoByEmail($email) {
		$sql = "select * from user where email='{$email}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $result[0];
		}
		else{
			return false;
		}
	}
	
	function setUserPassword($id, $pwd) {
		$sql = "update user set "; 
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " user_password = '{$pwd}'";	
		}
		$sql .= " where user_id = '{$id}'";
	
		$query = $this->db->query( $sql);
		return true;
	}
	
	function getUserInfoById($id) {
		$sql = "select * from user where user_id = '{$id}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $result[0];
		}
		else{
			return false;
		}
	}
	
	function modifyUserInfoById($id, $username, $email, $pwd, $homephone, $mobilephone, $userrole, $vehicletype, $vehicle_reg) {
		$sql = "update user set 
			username = '{$username}',
			user_roles = '{$userrole}',
			homephone = '{$homephone}',
			mobilephone = '{$mobilephone}',
			email = '{$email}',
			vehicletype = '{$vehicletype}',
			vehicle_reg = '{$vehicle_reg}' ";
		
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " ,user_password = '{$pwd}'";	
		}
		$sql .= " where user_id = '{$id}'";
	
		$query = $this->db->query( $sql);
		return true;
	}
	
	function addUserInfo($supervisor_id, $company_id, $username, $email, $pwd, $homephone, $mobilephone, $userrole, $vehicletype, $vehicle_reg) {
		$sql = "insert into user set 
			username = '{$username}',
			user_roles = '{$userrole}',
			homephone = '{$homephone}',
			mobilephone = '{$mobilephone}',
			email = '{$email}',
			vehicletype = '{$vehicletype}',
			vehicle_reg = '{$vehicle_reg}', 
			company_id = '{$company_id}',
			regdate = NOW()
			 
			 ";
		
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " ,user_password = '{$pwd}'";	
		}
		
		$query = $this->db->query( $sql);
		$user_id = $this->db->insert_id();
		
		$sql = "insert into supervisor_user_rel ( supervisor_id, user_id) values('{$supervisor_id}', '{$user_id}') ";
		$this->db->query($sql);
		
		return $user_id;
	}
	
	
	function addUserLocation($user_id, $lat, $long, $battery) {
		
		// check if duplicated record
		$sql = "select * from user_location_history where user_id = '{$user_id}' order by history_id desc limit 0, 1";
		$query = $this->db->query( $sql);
		$result = $query->result();
		$lastInfo = $result[0];
		if ($lastInfo->latitude == $lat && $lastInfo->longitude == $long) return;
		
		$sql = "insert into user_location_history set  
					user_id = '{$user_id}',
					start_date_time = now(), 
					end_date_time = now(), 
					latitude = '{$lat}',
					longitude = '{$long}', 
					battery = '{$battery}'
		";
		$this->db->query($sql);
		
		$sql = "update user set last_latitude = '{$lat}', last_longitude = '{$long}', battery = '{$battery}' where user_id = '{$user_id}' ";
		$this->db->query($sql);
	}
	function addUserLocationByLogin($user_id, $lat, $long) {
		$sql = "update user set last_latitude = '{$lat}', last_longitude = '{$long}'  where user_id = '{$user_id}' ";
		$this->db->query($sql);
		
		$sql = "select * from user_location_history where user_id = '{$user_id}' order by history_id desc limit 0, 1";
		$query = $this->db->query( $sql);
		$result = $query->result();
		$lastInfo = $result[0];
		if ($lastInfo->latitude == $lat && $lastInfo->longitude == $long) return;
		
		$sql = "insert into user_location_history set  
					user_id = '{$user_id}',
					start_date_time = now(), 
					end_date_time = now(), 
					latitude = '{$lat}',
					longitude = '{$long}'
		";
		$this->db->query($sql);
	}
	
	function getUserLocations($user_id) {
		$sql = "select * from user_location_history where user_id = ".$user_id;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getUserLocationsforTask($user_id,$task_id) {
		$sql = "select * from user_location_history a where user_id = ".$user_id." and exists (select 1 from task t where t.user_id = a.user_id and  t.task_id = ".$task_id."  and a.start_date_time > t.actual_start)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getMovementForTask($userId, $start, $end){
	    $sql = "SELECT * FROM user_location_history ulh WHERE user_id=$userId ";
	    $sql .= " AND start_date_time >= '$start'";
	    
	    if ($end != null && $end != "")
	       $sql .= " AND end_date_time <= '$end'";
	    
	    $sql .= " ORDER BY start_date_time ASC";
	    
	    return $this->db->query($sql)->result_array();
	}
	
	function deleteUserHistory($history_id) {
		$sql = "delete from user_location_history where history_id = ".$history_id;
		$this->db->query($sql);
	}
	
	function setUserGcmKey($useridx, $regid) {
		$sql = "update user set gcmid = '{$regid}' where user_id = '{$useridx}'";
		$query = $this->db->query($sql);
	}
	
	function setTaskSOS ($task_id, $user_id) {
		
		// check this task is in sosmain table 
		$sql = "select count(*) as cnt from sosmain where task_id = '{$task_id}' and original_user = '{$user_id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if ($result[0]->cnt > 0) return 0;
		
		// change task status 
		$sql = "update task set SOS_STATUS = 'Yes', task_Status = 'PENDSCHEDULE' where task_id = '{$task_id}'";
		$query = $this->db->query($sql);
		
		// insert a new record sosmain table 
		$sql = "insert into sosmain set task_id = '{$task_id}', original_user = '{$user_id}', starttime = NOW(), status=0";
		$query = $this->db->query($sql);
		$sosmain_id = $this->db->insert_id();
		return $sosmain_id;
	}
	function setSOSResponse($sosmain_id, $user_id, $response, $user_addr) {
		$sql = "insert into sosresponse set sosmain_id = '{$sosmain_id}', user_id = '{$user_id}', response = '{$response}', userlocation = '{$user_addr}', responsetime = NOW()";
		$query = $this->db->query($sql);
	}
	
	function findAnotherUsersOfCompany($task_id, $user_id) {
		// get company id of this task
		$sql = "select * from task where task_id = '{$task_id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		$company_id = $result[0]->company_id;
		
		// get another supervisor ids for this company of task
		$sql = "select * from user where company_id = '{$company_id}' and user_id <> '{$user_id}' and gcmid is not null ";
		$query = $this->db->query($sql);
		$anotherUsers = $query->result();
		return $anotherUsers;
	}
	
	function checkPermissionForUserData($superid, $userid){
	    $rows = $this->db->get_where('supervisor_user_rel', array('supervisor_id'=>$superid, 'user_id'=>$userid))->num_rows();
	    if ($rows == 0)
	        return false;
	    else return true;
	}
}