<?php
Class Member extends CI_Model{
	
	function __construct(){
	   	parent::__construct();
		$this->load->helper('date');
	}
	function loginCompany($email, $pwd) {
		// check email is already registerd 
		$pwd = hash("sha512", $pwd); 
		
		$sql = "select * from company where company_email='{$email}' and company_password='{$pwd}'";
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
	function verifyCompanyEmail($token) {
		$sql = "select * from company where verifytoken='{$token}'";
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
	function updateVerifyCompanyEmail($token) {
		$sql = "update company set verifyemail = 1 where verifytoken='{$token}'";
		$query = $this->db->query( $sql);
	}
	function registerCompany($reqArray) {
			// check username is already registerd 
			$sql = "select * from company where company_email='{$reqArray['email']}'";
			$query = $this->db->query( $sql);
			$result = $query->result();
			if( $query->num_rows() > 0){
				return "That email is not available, please choose another email";
			}
			
			$token = md5(time());	
			$pwd = hash("sha512", $reqArray['pwd']);
			$sql = "insert into company(company_email, company_name, company_password, company_address, company_phone, trial_limit_user, trial_limit_sup, verifytoken, regdate, timezone) 
				values ('{$reqArray['email']}', '{$reqArray['companyname']}', '{$pwd}', '{$reqArray['address']}'
					, '{$reqArray['mobile']}', '{$reqArray['trial_limit_user']}', '{$reqArray['trial_limit_sup']}', '{$token}', NOW(), '{$reqArray['timezone']}')
				";
			$query = $this->db->query( $sql);
			
			$mailTitle = "[Geoclarity] Verify your compnay email.";
			$mailContent = "Dear ".$reqArray['companyname']."<br/>";
			$mailContent .= "Your company information have successfully registered our site.<br/><br/> ";
			$mailContent .= "Please verify your email by using below link. <br/>";
			$mailContent .="<a href='http://geoclarity.com/roofzouk/index.php/company/verifyemail?token={$token}'>Verify Email</a>";
			
			$headers = "Content-type: text/html; charset=UTF-8\r\n" . "From: info@geoclarity.com";
			mail($reqArray['email'], $mailTitle, $mailContent, $headers);
			return "";
		}
	function modifyCompany($id, $name, $address, $phone, $profileimage, $timezone, $pwd) {
		$sql = "update company set company_name = '{$name}', company_address = '{$address}', company_phone = '{$phone}', profileimage= '{$profileimage}', timezone = '{$timezone}' ";
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= ", company_password = '{$pwd}' ";
		}
		$sql .= " where company_id = '{$id}'  ";
		$this->db->query($sql);
	}
	function importSupervisorByCompany($info, $company_id) {
		// check email is already registerd 
		
		$sql = "insert into supervisor(supervisor_name, supervisor_password, department, teamsize, home_phone, office_phone, mobile_phone, address, company_id, regdate) 
			values ('{$info[0]}','{$info[1]}','{$info[2]}','{$info[3]}','{$info[4]}','{$info[5]}','{$info[6]}','{$info[7]}', '{$company_id}', NOW()) "; 
		$query = $this->db->query( $sql);
		return $this->db->insert_id();
	}
	function importUserByCompany($info, $company_id) {
		// check email is already registerd 
		$sql = "select * from user where email='{$info[4]}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			return 0;
		}
		$sql = "insert into user(username, user_roles, homephone, mobilephone, email, company_name, vehicletype, vehicle_reg, company_id, regdate) 
			values ('{$info[0]}','{$info[1]}','{$info[2]}','{$info[3]}','{$info[4]}','{$info[5]}','{$info[6]}','{$info[7]}', '{$company_id}', NOW()) "; 
		$query = $this->db->query( $sql);
		return $this->db->insert_id();
	}
	function FindCompanyUserByEmail($email, $company_id) {
		$sql = "select * from user where email = '{$email}' and company_id = '{$company_id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if ($query->num_rows() > 0) {
			return $result[0]->user_id;
		} else {
			return 0;
		}
	}
	function FindUserByEmail($email) {
		$sql = "select * from user where email = '{$email}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if ($query->num_rows() > 0) {
			return $result[0]->user_id;
		} else {
			return 0;
		}
	}
	
	function FindContact($company_id, $name, $address, $city, $phone) {
		$sql = "select * from contact where company_id = '{$company_id}' and contact_name = '{$name}' and contact_address = '{$address}' and contact_city = '{$city}' and contact_phone = '{$phone}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if ($query->num_rows() > 0) {
			return $result[0];
		} else {
			return null;
		}
	}
	function InsertContact($company_id, $name, $address, $city, $phone, $lat, $long) {
		$sql = "insert into contact (company_id, contact_name, contact_phone, contact_address, contact_city, contact_lat, contact_lng) 
			values('{$company_id}', '{$name}', '{$phone}', '{$address}', '{$city}', '{$lat}', '{$long}')";
		$query = $this->db->query($sql);
	}
	function getContactInfoById($id) {
		$sql = "select * from contact where contact_id='{$id}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $row;
		}
		else{
			return null;
		}
	}
	function searchContacts($search, $company_id) {
		//$sql = "select * from contact where company_id = '{$company_id}' and  (contact_name like '%{$search}%' or contact_phone like '%{$search}%' or contact_address like '%{$search}%')";
		$sql = "select * from contact where company_id = '{$company_id}' and  (contact_name like '%{$search}%' or contact_phone like '%{$search}%' or contact_address like '%{$search}%')";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	function searchContactByName($name, $company_id) {
		$sql = "select * from contact where company_id = '{$company_id}' and  contact_name = '{$name}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		
		return $result[0];
	}
	function searchUsers($search, $company_id) {
		$sql = "select * from user where company_id = '{$company_id}' and  (username like '%{$search}%' or user_roles like '%{$search}%' or homephone like '%{$search}%' 
				or mobilephone like '%{$search}%' or email like '%{$search}%' or vehicletype like '%{$search}%' or vehicle_reg like '%{$search}%' 
			)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function searchSupervisors($search, $company_id) {
		$sql = "select * from supervisor where company_id = '{$company_id}' and  (supervisor_name like '%{$search}%' or department like '%{$search}%' or teamsize like '%{$search}%' 
				or email like '%{$search}%' or home_phone like '%{$search}%' or office_phone like '%{$search}%' or mobile_phone like '%{$search}%' or address like '%{$search}%' 
			)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getTopSupervisors($where, $company_id) {
		$sql = "
			select B.* 
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) = DATE(NOW())) as cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) = DATE(NOW()) and task_Status = 'COMPLETED') as completed_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) = DATE(NOW()) and task_Status = 'CANCELLED') as cancelled_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) = DATE(NOW()) and task_Status = 'DELAYED') as delayed_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) = DATE(NOW()) and SOS_STATUS = 'Yes') as sos_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) >= (NOW() - Interval 1 month) ) as month_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) >= (NOW() - Interval 1 month) and task_Status = 'COMPLETED') as month_completed_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) >= (NOW() - Interval 1 month) and task_Status = 'CANCELLED') as month_cancelled_cnt
			, (select count(*) from task where user_id in (select user_id from supervisor_user_rel where supervisor_user_rel.supervisor_id  = B.supervisor_id) and DATE(scheduled_Start) >= (NOW() - Interval 1 month) and task_Status = 'DELAYED') as month_delayed_cnt
			from 
			( select  supervisor_user_rel.supervisor_id , count(*) as cnt 
						from task left join supervisor_user_rel on task.user_id =supervisor_user_rel.user_id   
						where company_id = {$company_id} 
						";
		if ($where!="") $sql .= " and ".$where;						
		$sql .=	" 
			group by supervisor_id 
			order by cnt desc limit 0, 10 ) 
			A left join supervisor B on A.supervisor_id = B.supervisor_id
		";
		//echo $sql;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	
	function loginSupervisor($email, $pwd) {
		// check email is already registerd 
		$pwd = hash("sha512", $pwd);
		$sql = "select supervisor.*, company.profileimage, company.timezone  
				from supervisor 
				left join company on supervisor.company_id = company.company_id 
				where supervisor.email='{$email}' and supervisor.supervisor_password='{$pwd}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $row;
		}
		else{
			return false;
		}
	}
	
	function getTopUsers($where, $supervisor_id) {
		$sql = "
			select B.* 
				, (select count(*) from task where task.user_id = B.user_id ) as cnt 
				, (select count(*) from task where task.user_id = B.user_id and task_Status = 'COMPLETED') as completed_cnt
				, (select count(*) from task where task.user_id = B.user_id and task_Status = 'CANCELLED') as cancelled_cnt
				, (select count(*) from task where task.user_id = B.user_id and task_Status = 'DELAYED') as delayed_cnt
				, (select count(*) from task where task.user_id = B.user_id and SOS_STATUS = 'Yes') as sos_cnt
			from 
			( select  task.user_id , count(*) as cnt 
						from task left join supervisor_user_rel on task.user_id =supervisor_user_rel.user_id   
						where supervisor_id = {$supervisor_id} 
						";
		if ($where!="") $sql .= " and ".$where;						
		$sql .=	" 
			group by task.user_id 
			order by cnt desc limit 0, 10 ) 
			A left join user B on A.user_id = B.user_id
		";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function searchUsersBySupervisor($search, $supervisor_id) {
		$sql = "select * from user where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') and  (username like '%{$search}%' or user_roles like '%{$search}%' or homephone like '%{$search}%' 
				or mobilephone like '%{$search}%' or email like '%{$search}%' or vehicletype like '%{$search}%' or vehicle_reg like '%{$search}%' 
			)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function modifySupervisor($pwd) {
		$sql = "update supervisor set supervisor_name = '{$_SESSION['supervisor']->supervisor_name}', 
					department = '{$_SESSION['supervisor']->department}',
					teamsize = '{$_SESSION['supervisor']->teamsize}',
					home_phone = '{$_SESSION['supervisor']->home_phone}',
					office_phone = '{$_SESSION['supervisor']->office_phone}',
					mobile_phone = '{$_SESSION['supervisor']->mobile_phone}',
					address = '{$_SESSION['supervisor']->address}' ";
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= ", supervisor_password = '{$pwd}' ";
		}
		
		$sql .= "where supervisor_id = '{$_SESSION['supervisor']->supervisor_id}'  ";
		$this->db->query($sql);
	}
	
	function checkCompanyName($name) {
		$sql = "select * from company where company_name='{$name}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			return $result[0]->company_id;
		}
		else{
			return 0;
		}
	}
	
	function registerSupervisor($reqArray, $token) {
			// check username is already registerd 
			$sql = "select * from supervisor where email='{$reqArray['email']}'";
			$query = $this->db->query( $sql);
			$result = $query->result();
			if( $query->num_rows() > 0){
				return "That email is not available, please choose another email";
			}
				
			$pwd = hash("sha512", $reqArray['pwd']);
			$sql = "insert into supervisor set 
					email = '{$reqArray['email']}', 
					supervisor_name = '{$reqArray['supervisor_name']}', 
					department = '{$reqArray['department']}',
					teamsize = '{$reqArray['teamsize']}',
					home_phone = '{$reqArray['home_phone']}',
					office_phone = '{$reqArray['office_phone']}',
					mobile_phone = '{$reqArray['mobile_phone']}',
					address = '{$reqArray['address']}', 
					verifytoken = '{$token}', 
					supervisor_password = '{$pwd}', 
					company_id = '{$reqArray['company_id']}', 
					regdate = NOW() 
				";
			$query = $this->db->query( $sql);
			
			
			return "";
		}
		
	function verifySupervisorEmail($token) {
		$sql = "select * from supervisor where verifytoken='{$token}'";
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
	
	function updateVerifySupervisorEmail($token) {
		$sql = "update supervisor set verifyemail = 1 where verifytoken='{$token}'";
		$query = $this->db->query( $sql);
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
		
		$plainPwd = $pwd;
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " ,user_password = '{$pwd}'";	
		}
		
		$query = $this->db->query( $sql);
		$user_id = $this->db->insert_id();
		
		$sql = "insert into supervisor_user_rel ( supervisor_id, user_id) values('{$supervisor_id}', '{$user_id}') ";
		$this->db->query($sql);
		
		$mailTitle = "[Geoclarity] Welcome to registered in our site.";
			$mailContent = "Dear ".$username."<br/>";
			$mailContent .= "You have registered to RoofZouk website.<br/><br/> ";
			$mailContent .= "Your password is {$plainPwd}. <br/>";
			
			$headers = "Content-type: text/html; charset=UTF-8\r\n" . "From: info@geoclarity.com";
			mail($email, $mailTitle, $mailContent, $headers);
		
		return true;
	}
	
	function getSupervisorInfoById($id) {
		$sql = "select supervisor.*, company.profileimage, company.timezone  
				from supervisor 
				left join company on supervisor.company_id = company.company_id  
				where supervisor.supervisor_id = '{$id}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			
			return $row;
		}
		else{
			return false;
		}
	}
	
	
	function getSupervisorInfoByEmail($email) {
		// check email is already registerd 
		$sql = "select *  
				from supervisor 
				where supervisor.email='{$email}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $row;
		}
		else{
			return null;
		}
	}
	
	function setSupervisorPassword($id, $pwd) {
		$sql = "update supervisor set "; 
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " supervisor_password = '{$pwd}'";	
		}
		$sql .= " where supervisor_id = '{$id}'";
	
		$query = $this->db->query( $sql);
		return true;
	}
	
	function getCompanyInfoByEmail($email) {
		$sql = "select * from company where company_email='{$email}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $row;
		}
		else{
			return null;
		}
	}
	function getCompanyInfoById($id) {
		$sql = "select * from company where company_id='{$id}'";
		$query = $this->db->query( $sql);
		$result = $query->result();
		if( $query->num_rows() >= 1){
			$row = $result[0];
			return $row;
		}
		else{
			return null;
		}
	}
	function setCompanyPassword($id, $pwd) {
		$sql = "update company set "; 
		if ($pwd != "") {
			$pwd = hash("sha512", $pwd);
			$sql .= " company_password = '{$pwd}'";	
		}
		$sql .= " where company_id = '{$id}'";
	
		$query = $this->db->query( $sql);
		return true;
	}
	
}