<?php
Class Task extends CI_Model{
	
	function __construct(){
	   	parent::__construct();
		$this->load->helper('date');
	}
	function importTaskByCompany($info, $company_id, $lat, $long) {
		// check email is already registerd 
		$sql = "insert into task(TasktypeCategory, creation_date, contact_name, contact_address, Region, contact_phone, user_id, scheduled_Start, scheduled_End, postcode, country, company_id, task_lat, task_long) 
			values ('{$info[0]}',NOW(),'{$info[1]}','{$info[2]}','{$info[3]}','{$info[4]}','{$info[5]}','{$info[6]}','{$info[7]}', '{$info[8]}','{$info[9]}', '{$company_id}', '{$lat}', '{$long}') "; 
		$query = $this->db->query( $sql);
		return $this->db->insert_id();
	}
	
	function searchTasks($search, $company_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where company_id = '{$company_id}' and  (TasktypeCategory like '%{$search}%' or contact_name like '%{$search}%' or contact_phone like '%{$search}%' or contact_address like '%{$search}%' 
					or task_Status like '%{$search}%' or postcode like '%{$search}%' or country like '%{$search}%' 		 
			)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getAllTasks($where, $company_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where company_id = '{$company_id}'  "; 
		if ($where != "") $sql .= "and ({$where})";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getTaskCountByMap($status, $lat1, $long1, $lat2, $long2, $where, $company_id) {
		$sql = "select count(*) as cnt from task where company_id = '{$company_id}' and SOS_STATUS = 'No' and task_Status = '{$status}' and  (task_lat >= {$lat1} and task_lat <={$lat2} and task_long >= {$long1} and task_long <= {$long2})";
		if ($where != "" ) $sql .= " and ".$where;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->cnt;
	}
	function getTaskSOSCountByMap($lat1, $long1, $lat2, $long2, $where, $company_id) {
		$sql = "select count(*) as cnt from task where company_id = '{$company_id}' and SOS_STATUS = 'Yes'  and  (task_lat >= {$lat1} and task_lat <={$lat2} and task_long >= {$long1} and task_long <= {$long2})";
		if ($where != "" ) $sql .= " and ".$where;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->cnt;
	}
	
	function searchRegionsTasks($region, $company_id) {
		$sql = "select  region, postcode, count(*) as cnt 
		,  (select count(*) from task B where B.postcode = task.postcode and B.SOS_STATUS = 'Yes') as sos_count 
		,  (select count(*) from task B where B.postcode = task.postcode and B.task_Status = 'CANCELLED') as cancelled_count 
		,  (select count(*) from task B where B.postcode = task.postcode and B.task_Status = 'COMPLETED') as completed_count 
		,  (select count(*) from task B where B.postcode = task.postcode and B.task_Status = 'DELAYED') as delayed_count 
		from task  where region like '%{$region}%' and company_id = '{$company_id}'
		group by region, postcode order by cnt desc ";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getRegionNameByPostcode($postcode) {
		$sql = "select region from task where postcode = '{$postcode}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->region;
	}
	
	function getSupListByPostcode($postcode, $company_id) {
		$sql = "
			select B.*, A.* from 
			( select supervisor_id, sum(cnt) as cnt,  sum(sos_count) as sos_count,  sum(cancelled_count) as cancelled_count,  sum(completed_count) as completed_count,  sum(delayed_count) as delayed_count  from 
			( select  supervisor_id, count(*) as cnt 
				,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.SOS_STATUS = 'Yes') as sos_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'CANCELLED') as cancelled_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'COMPLETED') as completed_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'DELAYED') as delayed_count  
						from task  
						left join supervisor_user_rel on task.user_id =supervisor_user_rel.user_id
						where postcode = '{$postcode}' and company_id = '{$company_id}'
			group by supervisor_id, task.user_id 
			) AA group by  supervisor_id ) 
			A left join supervisor B on A.supervisor_id = B.supervisor_id
			order by A.cnt desc
		";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getAllSupervisorTasks($where, $supervisor_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') "; 
		if ($where != "") $sql .= "and ({$where})";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getSupervisorTaskCountByMap($status, $lat1, $long1, $lat2, $long2, $where, $supervisor_id) {
		$sql = "select count(*) as cnt from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') and SOS_STATUS = 'No' and task_Status = '{$status}' and  (task_lat >= {$lat1} and task_lat <={$lat2} and task_long >= {$long1} and task_long <= {$long2})";
		if ($where != "" ) $sql .= " and ".$where;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->cnt;
	}
	function getSupervisorTaskSOSCountByMap($lat1, $long1, $lat2, $long2, $where, $supervisor_id) {
		$sql = "select count(*) as cnt from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') and SOS_STATUS = 'Yes'  and  (task_lat >= {$lat1} and task_lat <={$lat2} and task_long >= {$long1} and task_long <= {$long2})";
		if ($where != "" ) $sql .= " and ".$where;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->cnt;
	}
	
	function searchTasksBySupervisor($search, $supervisor_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') and  (TasktypeCategory like '%{$search}%' or contact_name like '%{$search}%' or contact_phone like '%{$search}%' or contact_address like '%{$search}%' 
					or task_Status like '%{$search}%' or postcode like '%{$search}%' or country like '%{$search}%' 		 
			)";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function findTasks($search, $supervisor_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') "; 
			
		if ($search != "") {
			$sql .=" and ".$search;
		}
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	function searchRegionsTasksBySupervisor($region, $supervisor_id) {
		
		// TODO: need to fix 
		$sql = "select  region, postcode, count(*) as cnt 
		,  (select count(*) from task B where B.postcode = task.postcode and B.user_id = task.user_id  and B.SOS_STATUS = 'Yes') as sos_count 
		,  (select count(*) from task B where B.postcode = task.postcode  and B.user_id = task.user_id and B.task_Status = 'CANCELLED') as cancelled_count 
		,  (select count(*) from task B where B.postcode = task.postcode and B.user_id = task.user_id  and B.task_Status = 'COMPLETED') as completed_count 
		,  (select count(*) from task B where B.postcode = task.postcode and B.user_id = task.user_id  and B.task_Status = 'DELAYED') as delayed_count 
		from task  where region like '%{$region}%' and user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}')
		group by region, postcode order by cnt desc ";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	function getAllTasksBySupervisor($where, $supervisor_id) {
		$sql = "select *, (select email from user where user.user_id = task.user_id) as user_email from task where user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}') "; 
		if ($where != "") $sql .= "and ({$where})";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	function getUserListByPostcode($postcode, $supervisor_id) {
		$sql = "
			select B.*, A.* from 
			( select user_id, sum(cnt) as cnt,  sum(sos_count) as sos_count,  sum(cancelled_count) as cancelled_count,  sum(completed_count) as completed_count,  sum(delayed_count) as delayed_count  from 
			( select  task.user_id, count(*) as cnt 
				,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.SOS_STATUS = 'Yes') as sos_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'CANCELLED') as cancelled_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'COMPLETED') as completed_count 
		,  (select count(*) from task B where B.user_id = task.user_id and B.postcode = task.postcode and B.task_Status = 'DELAYED') as delayed_count  
						from task  
						where postcode = '{$postcode}' and task.user_id in (select user_id from supervisor_user_rel where supervisor_id = '{$supervisor_id}')
			group by task.user_id 
			) AA group by  user_id ) 
			A left join user B on A.user_id = B.user_id
			order by A.cnt desc
		";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getTaskInfoById($id) {
		$sql = "select * from task where task_id = '{$id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0];
	}
	
	function updateTaskInfo($id, $companyid,  $userid, $sosstatus, $TasktypeCategory, $task_Status, $scheduled_Start, $scheduled_End, $contact_name, $contact_address, $contact_phone, $Region, $postcode, $country, $lat, $long, $notes) {
		// check email is already registerd 
		$sql = " update task set 
			TasktypeCategory = '{$TasktypeCategory}',
			task_Status = '{$task_Status}',
			 
			contact_name = '{$contact_name}', 
			contact_address = '{$contact_address}', 
			Region = '{$Region}', 
			contact_phone = '{$contact_phone}', 
			user_id = '{$userid}', 
			scheduled_Start = '{$scheduled_Start}', 
			scheduled_End = '{$scheduled_End}', 
			postcode = '{$postcode}', 
			country = '{$country}', 
			company_id = '{$companyid}', 
			task_lat = '{$lat}', 
			task_long = '{$long}', 
			notes = '{$notes}'
			where task_id = '{$id}' 
			"; 
		$query = $this->db->query( $sql);
	}
	
	function addTaskInfo($companyid,  $userid, $sosstatus, $TasktypeCategory, $task_Status, $scheduled_Start, $scheduled_End, $contact_name, $contact_address, $contact_phone, $Region, $postcode, $country, $lat, $long, $notes) {
		// check email is already registerd 
		$sql = " insert into task set 
			TasktypeCategory = '{$TasktypeCategory}',
			task_Status = '{$task_Status}',
			 
			contact_name = '{$contact_name}', 
			contact_address = '{$contact_address}', 
			Region = '{$Region}', 
			contact_phone = '{$contact_phone}', 
			user_id = '{$userid}', 
			scheduled_Start = '{$scheduled_Start}', 
			scheduled_End = '{$scheduled_End}', 
			postcode = '{$postcode}', 
			country = '{$country}', 
			company_id = '{$companyid}', 
			task_lat = '{$lat}', 
			task_long = '{$long}', 
			notes = '{$notes}'
		"; 
		$query = $this->db->query( $sql);
	}
	
	function CompleteTask($task_id) {
		$sql = "update task set actual_end = NOW(), task_Status = 'COMPLETED' where task_id = {$task_id}";
		$query = $this->db->query($sql);
	}
	
	
	function CancelTask($task_id) {
		$sql = "update task set actual_end = NOW(), task_Status = 'CANCELLED' where task_id = {$task_id}";
		$query = $this->db->query($sql);
	}
	
	function FindUserProcessTask($user_id) {
		$sql = "select * from task where user_id = '{$user_id}' and task_Status = 'INPROGRESS' limit 0, 1";
		$query = $this->db->query($sql);
		$result = $query->result();
		if( $query->num_rows() >= 1) return $result[0];
		
		return null;
	}
 	
	function FindUserCreatedTask($user_id) {
		$sql = "select * from task where user_id = '{$user_id}' and (task_Status = 'CREATED' or task_Status = 'RESCHEDULED') order by scheduled_Start asc limit 0, 1";
		$query = $this->db->query($sql);
		$result = $query->result();
		if( $query->num_rows() >= 1) return $result[0];
		return null;
	}
	
	function ActualStartTask($task_id) {
		$sql = "update task set actual_Start = NOW() where task_id = {$task_id} and actual_Start is NULL";
		$query = $this->db->query($sql);
	}
	
	function GetSOSTask($sosmain_id) {
		$sql = "select task.*, sosmain.status as sos_status from task 
			left join sosmain on sosmain.task_id = task.task_id 
			where sosmain.sosmain_id = '{$sosmain_id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if( $query->num_rows() >= 1) return $result[0];
		return null;
	} 
	
	function GetCurrentSOSCount($sup_id) {
		$sql = " select count(*) as cnt from sosmain 
			where status = 0 and original_user in (select user_id from supervisor_user_rel where supervisor_id = '{$sup_id}')  
				";
		
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->cnt;
	}
	
	function getCurrentSOSTasks($sup_id) {
		$sql = "select task.* , user.email as user_email, sosmain.* 
			from sosmain left join task on sosmain.task_id = task.task_id  
			left join user on sosmain.original_user = user.user_id 
			where sosmain.status = 0 and original_user in (select user_id from supervisor_user_rel where supervisor_id = '{$sup_id}' )
			order by sosmain.sosmain_id desc
			";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	function getPastSOSTasks($sup_id) {
		$sql = "select task.* , user.email as user_email, sosmain.*, B.email as process_email 
			from sosmain left join task on sosmain.task_id = task.task_id  
			left join user on sosmain.original_user = user.user_id  
			left join user B on sosmain.process_user = B.user_id
			where sosmain.status > 0  and original_user in (select user_id from supervisor_user_rel where supervisor_id = '{$sup_id}' )
			order by sosmain.sosmain_id desc
			";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	function getSosResponseList($sosmain_id) {
		$sql = "
			select sosresponse.*, user.email as user_email  
			from sosresponse left join user on sosresponse.user_id = user.user_id 
			where sosmain_id = '{$sosmain_id}'
			order by sosresponse_id desc
		";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getSosTaskInfoById($sosmain_id) {
		$sql = "select * from sosmain where sosmain_id = '{$sosmain_id}'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0];
	}
	function cancelSosTask($sosmain_id, $task_id) {
		$sql = "update sosmain set status = 2, endtime = Now() where sosmain_id = '{$sosmain_id}'";
		$query = $this->db->query($sql);
		
		$sql = "update task set task_Status = 'CANCELLED' where task_id = '{$task_id}'";
		$query = $this->db->query($sql);
	}
	function assisnSosTask($sosmain_id, $task_id, $user_id) {
		$sql = "update sosmain set status = 1, process_user = '{$user_id}', endtime = Now() where sosmain_id = '{$sosmain_id}'";
		$query = $this->db->query($sql);
		
		$sql = "update task set task_Status = 'RESCHEDULED', user_id = '{$user_id}' where task_id = '{$task_id}'";
		$query = $this->db->query($sql);
	}
	
	function getCurrentSOSTasksAll() {
		$sql = "select task.* , user.email as user_email, sosmain.* 
			from sosmain left join task on sosmain.task_id = task.task_id  
			left join user on sosmain.original_user = user.user_id 
			where sosmain.status = 0 and  unix_timestamp(starttime) <= unix_timestamp(CURRENT_TIMESTAMP - INTERVAL 5 MINUTE) 
			order by sosmain.sosmain_id desc
			";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
	
	function getUserTaskHistory($user_id) {
		$sql = "select * from task where user_id = '{$user_id}' and task_Status in ( 'CANCELLED' ,'COMPLETED') order by  actual_end desc ";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
}