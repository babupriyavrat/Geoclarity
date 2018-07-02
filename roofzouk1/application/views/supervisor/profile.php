
<div class="container" style="margin-top:100px;">	
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('my_account');?></b></div>
				<div class="panel-body">
					<form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo get_phrase('email');?></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" value="<?php echo $_SESSION['supervisor']->email?>" readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('password');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="pwd" name="pwd" placeholder="Password (Empty if you don't change password)" value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('supervisor_name');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="Supervisorname" name="Supervisorname" placeholder="Supervisor NAME" required value="<?php echo $_SESSION['supervisor']->supervisor_name?>">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('department');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="department" name="department" placeholder="Department" required value="<?php echo $_SESSION['supervisor']->department?>">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('teamsize');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id=""teamsize"" name="teamsize" placeholder="Team Size" required value="<?php echo $_SESSION['supervisor']->teamsize?>">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('home_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="homePhone" name="homePhone" placeholder="Home Phone #" required value="<?php echo $_SESSION['supervisor']->home_phone?>">
						    </div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('office_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="officePhone" name="officePhone" placeholder="Office Phone #" required value="<?php echo $_SESSION['supervisor']->office_phone?>">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('mobile_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="mobilephone" name="mobilephone" placeholder="Mobile Phone #" required value="<?php echo $_SESSION['supervisor']->mobile_phone?>">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('supervisor_address');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="address" name="address" placeholder="Supervisor ADDRESS" required value="<?php echo $_SESSION['supervisor']->address?>">
						    </div>
						</div>
						
						
						<div class="form-group row">
							<div class="col-sm-10 col-sm-offset-2 text-left">
								<input type="submit" class="btn standard-button" value="<?php echo get_phrase('save');?>" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function () {
            $('#frm').formValidation({
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