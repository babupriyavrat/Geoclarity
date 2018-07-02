
<div class="container" style="margin-top:100px;">	
<div class="row">
		<div class="col-sm-12 text-right">
			<?php 
			
			$zonelist = array('Kwajalein' => -12.00, 'Pacific/Midway' => -11.00, 'Pacific/Honolulu' => -10.00, 'America/Anchorage' => -9.00, 'America/Los_Angeles' => -8.00, 'America/Denver' => -7.00, 'America/Tegucigalpa' => -6.00, 'America/New_York' => -5.00, 'America/Caracas' => -4.30, 'America/Halifax' => -4.00, 'America/St_Johns' => -3.30, 'America/Argentina/Buenos_Aires' => -3.00, 'America/Sao_Paulo' => -3.00, 'Atlantic/South_Georgia' => -2.00, 'Atlantic/Azores' => -1.00, 'Europe/Dublin' => 0, 'Europe/Belgrade' => 1.00, 'Europe/Minsk' => 2.00, 'Asia/Kuwait' => 3.00, 'Asia/Tehran' => 3.30, 'Asia/Muscat' => 4.00, 'Asia/Yekaterinburg' => 5.00, 'Asia/Kolkata' => 5.30, 'Asia/Katmandu' => 5.45, 'Asia/Dhaka' => 6.00, 'Asia/Rangoon' => 6.30, 'Asia/Krasnoyarsk' => 7.00, 'Asia/Brunei' => 8.00, 'Asia/Seoul' => 9.00, 'Australia/Darwin' => 9.30, 'Australia/Canberra' => 10.00, 'Asia/Magadan' => 11.00, 'Pacific/Fiji' => 12.00, 'Pacific/Tongatapu' => 13.00);
	        $index = array_keys($zonelist, $_SESSION['supervisor']->timezone);
	        date_default_timezone_set($index[0]);
			?>
			<span style="font-size:13px;"><?php echo get_phrase('current_time');?>: <?php echo date("Y-m-d H:i")?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('searched_contacts');?></b></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('name');?></th>
								<th><?php echo get_phrase('phone');?></th>
								<th><?php echo get_phrase('address');?></th>
								<th><?php echo get_phrase('city');?></th>
								<th><?php echo get_phrase('sms');?></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($ContactList as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->contact_name?></td>
								<td><?php echo $info->contact_phone?></td>
								<td><?php echo $info->contact_address?></td>
								<td><?php echo $info->contact_city?></td>
								<td><a href="#" onclick="ShowSMSWin(<?php echo $info->contact_id?>, '<?php echo $info->contact_phone?>'); return false;" >Send Sms</a></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('searched_tasks');?></b></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('task_category');?></th>
								<th><?php echo get_phrase('user');?></th>
								<th><?php echo get_phrase('schedule_start');?></th>
								<th><?php echo get_phrase('schedule_end');?></th>
								<th><?php echo get_phrase('contact_name');?></th>
								<th><?php echo get_phrase('contact_address');?></th>
								<th><?php echo get_phrase('contact_phone');?></th>
								<th><?php echo get_phrase('task_status');?></th>
								<th><?php echo get_phrase('notes');?></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($TaskList as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->TasktypeCategory?></td>
								<td><?php echo $info->user_email?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($info->scheduled_Start))?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($info->scheduled_End))?></td>
								<td><?php echo $info->contact_name?></td>
								<td><?php echo $info->contact_address?></td>
								<td><?php echo $info->contact_phone?></td>
								<td><?php echo $info->task_Status?></td>
								<td><?php echo $info->notes?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('searched_users');?></b></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('user_name');?></th>
								<th><?php echo get_phrase('user_role');?></th>
								<th><?php echo get_phrase('home_phone');?></th>
								<th><?php echo get_phrase('mobile_phone');?></th>
								<th><?php echo get_phrase('email');?></th>
								<th><?php echo get_phrase('vihicle_type');?></th>
								<th><?php echo get_phrase('vihicle_reg');?></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($UserList as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->username?></td>
								<td><?php echo $info->user_roles?></td>
								<td><?php echo $info->homephone?></td>
								<td><?php echo $info->mobilephone?></td>
								<td><?php echo $info->email?></td>
								<td><?php echo $info->vehicletype?></td>
								<td><?php echo $info->vehicle_reg?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo get_phrase('send_sms_to_contact');?></h4>
      </div>
      <div class="modal-body text-left">
      	<div class="form-group">
      		<label><?php echo get_phrase('phone');?></label>
      		<input class="form-control" id="sms_to" value="" readonly />
      		<input type="hidden" class="form-control" id="sms_to_id" value="" readonly />
      	</div>
      	<div class="form-group">
      		<label><?php echo get_phrase('sms_text');?></label>
      		<textarea class="form-control" id="sms_text" ></textarea>
      	</div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary" onclick="SendSMS();"><?php echo get_phrase('send');?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close');?></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function ShowSMSWin(id, phone) {
	$('#sms_to_id').val(id);
	$('#sms_to').val(phone);
	$('#sms_text').val("");
	$('#myModal').modal('show');
}

function SendSMS() {
	setWait();
	$.ajax({
		type : "post",
		datatype : "json",
		data : {
			contact_id : $('#sms_to_id').val(),
			msg: $('#sms_text').val()
		},
		url : "<?php echo base_url()?>index.php/supervisor/sendSms",
		success : function(retStr, code){
			$('#myModal').modal('hide');
			unsetWait();
			alert(retStr);
		}
	});
}
            //<![CDATA[
            //var dataForm = new RegisternForm('form-validate', true);
            $(document).ready(function () {
                $('.table').dataTable();
            });
            //]]>
</script>