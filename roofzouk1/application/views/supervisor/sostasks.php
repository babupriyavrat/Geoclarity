
<div class="container" style="margin-top:100px;">	
	
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('current_sos_tasks');?></b></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('task_category');?></th>
								<th><?php echo get_phrase('contact_name');?></th>
								<th><?php echo get_phrase('sos_request_time');?></th>
								<th><?php echo get_phrase('original_user');?></th>
								<th><?php echo get_phrase('other_users_response');?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($curTasks as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->TasktypeCategory?></td>
								<td><?php echo $info->contact_name?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($info->starttime))?></td>
								<td><?php echo $info->user_email?></td>
								<td style="text-align: left">
									<?php foreach ($info->resList as $resInfo) {?>
									<?php echo $resInfo->user_email?> - <?php echo $resInfo->response == 1 ? "Yes" : "No"?>
									
									<?php if ($resInfo->response == 1) {?>
									: <a href='<?php echo base_url()?>index.php/supervisor/SosAssignTask/<?php echo $info->sosmain_id?>?user_id=<?php echo $resInfo->user_id?>'>
									   <?php echo get_phrase('assign_task');?></a>
									<?php }?>
									<br/>
									<?php }?>
								</td>
								<td><a href='<?php echo base_url()?>index.php/supervisor/CancelSosTask/<?php echo $info->sosmain_id?>'>
								    <?php echo get_phrase('cancel_task');?></a></td>
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
				<div class="panel-heading"><b><?php echo get_phrase('past_sos_tasks');?></b></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('task_category');?></th>
								<th><?php echo get_phrase('contact_name');?></th>
								<th><?php echo get_phrase('sos_request_time');?></th>
								<th><?php echo get_phrase('original_user');?></th>
								<th><?php echo get_phrase('status');?></th>
								<th><?php echo get_phrase('assigned_user');?></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($pastTasks as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->TasktypeCategory?></td>
								<td><?php echo $info->contact_name?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($info->starttime))?></td>
								<td><?php echo $info->user_email?></td>
								<td>
									<?php echo $info->status == 1? "Assigned" : "Cancelled"?>
								</td>
								<td>
									<?php echo $info->process_email?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
	</div>	
</div>
<script type="text/javascript">
            //<![CDATA[
            //var dataForm = new RegisternForm('form-validate', true);
            $(document).ready(function () {
                $('.table').dataTable();
            });
            //]]>
        </script>