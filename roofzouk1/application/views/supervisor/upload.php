
<div class="container" style="margin-top:100px;">	
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('upload_tasks');?></b></div>
				<div class="panel-body">
					<form method="post" enctype="multipart/form-data" class="form">
						<input type="hidden" name="task" value="tasks">
						<?php if ($taskFailed!="") {?>
						<div class="alert alert-danger">
							<?php echo $taskFailed;?>
						</div>
						<?php }?>
						<?php if ($taskSuccess!="") {?>
						<div class="alert alert-success">
							<?php echo $taskSuccess;?>
						</div>
						<?php }?>
						<div class="form-group">
							<input type="file"  name="file" class="form-control" required>
						</div>
						<div class="form-group">
						<input type="submit" class="btn btn-primary"  value="<?php echo get_phrase('upload');?>" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>		
	
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('upload_users');?></b></div>
				<div class="panel-body">
					<form method="post" enctype="multipart/form-data" class="form">
						<input type="hidden" name="task" value="users">
						<?php if ($userFailed!="") {?>
						<div class="alert alert-danger">
							<?php echo $userFailed;?>
						</div>
						<?php }?>
						<?php if ($userSuccess!="") {?>
						<div class="alert alert-success">
							<?php echo $userSuccess;?>
						</div>
						<?php }?>
						<div class="form-group">
							<input type="file" name="file"  class="form-control" required>
						</div>
						<div class="form-group">
						<input type="submit" class="btn btn-primary" value="<?php echo get_phrase('upload');?>" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
            //<![CDATA[
            //var dataForm = new RegisternForm('form-validate', true);
            $(document).ready(function () {
                $('.form').formValidation({
                    framework: 'bootstrap',
                    icon: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                    }
                });

            });
            //]]>
        </script>