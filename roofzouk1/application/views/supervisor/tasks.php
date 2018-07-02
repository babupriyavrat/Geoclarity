
<div class="container" style="margin-top:100px;">	
	
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('all_tasks');?></b></div>
				<div class="panel-body">
					<div >
						<div class="text-left" style="margin-bottom: 10px;float:left">
						<input type="button" class="btn btn-primary" value="<?php echo get_phrase('add_task');?>" onclick="location.href='<?php echo base_url()?>index.php/supervisor/taskadd'"/>
						</div>
						<div class="text-left" style="float:right">
							<form method="post">
							<b><?php echo get_phrase('task_status');?></b> 
							<select name="filter_status" class="">
								<option value=""><?php echo get_phrase('all');?></option>
								<option value="CREATED" <?php echo $filter_status == "CREATED" ? "selected": ""?>><?php echo get_phrase('CREATED');?></option>
								<option value="PENDSCHEDULE" <?php echo $filter_status  == "PENDSCHEDULE" ? "selected": ""?>><?php echo get_phrase('PENDSCHEDULE');?></option>
								<option value="INPROGRESS" <?php echo $filter_status  == "INPROGRESS" ? "selected": ""?>><?php echo get_phrase('INPROGRESS');?></option>
								<option value="DELAYED" <?php echo $filter_status  == "DELAYED" ? "selected": ""?>><?php echo get_phrase('DELAYED');?></option>
								<option value="RESCHEDULED" <?php echo $filter_status  == "RESCHEDULED" ? "selected": ""?>><?php echo get_phrase('RESCHEDULED');?></option>
								<option value="COMPLETED" <?php echo $filter_status  == "COMPLETED" ? "selected": ""?>><?php echo get_phrase('COMPLETED');?></option>
								<option value="CANCELLED" <?php echo $filter_status  == "CANCELLED" ? "selected": ""?>><?php echo get_phrase('CANCELLED');?></option>
							</select>
							
							&nbsp;&nbsp;<b><?php echo get_phrase('date');?></b> 
							<select name="filter_date">
								<option value=""><?php echo get_phrase('all');?></option>
								<option value="1" <?php echo $filter_date == "1" ? "selected" : ""?>><?php echo get_phrase('today');?></option>
								<option value="2" <?php echo $filter_date == "2" ? "selected" : ""?>><?php echo get_phrase('yesterday');?></option>
								<option value="7" <?php echo $filter_date == "7" ? "selected" : ""?>><?php echo get_phrase('1_week');?></option>
								<option value="30" <?php echo $filter_date == "30" ? "selected" : ""?>><?php echo get_phrase('1_month');?></option>
							</select>
							&nbsp;<input type="submit" class="btn btn-info" value="Filter" />
							</form>
						</div>
						<div style="clear:both"></div>
					</div>
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
								<th></th>
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
								<td><a href='<?php echo base_url()?>index.php/supervisor/taskedit/<?php echo $info->task_id?>'><?php echo get_phrase('edit');?></a></td>
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