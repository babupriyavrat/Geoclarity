
<div class="container" style="margin-top:100px;">	
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('my_account');?></b></div>
				<div class="panel-body">
					<form class="home-horizontal" id="frm" method="post" enctype="multipart/form-data">
						<div class="form-group row">
							<label class="control-label col-sm-2"><?php echo get_phrase('email');?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo $_SESSION['company']->company_email?>" readonly />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label"><?php echo get_phrase('password');?></label>
							<div class="col-sm-10">
						    	<input type="text" class="form-control" id="pwd" name="pwd" placeholder="Password (Empty if you don't change password)" value="">
						    </div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><?php echo get_phrase('name');?></label>
							<div class="col-sm-10">
								<input name="name" type="text" class="form-control" value="<?php echo $_SESSION['company']->company_name?>" required />
							</div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><?php echo get_phrase('address');?></label>
							<div class="col-sm-10">
								<input name="address" type="text" class="form-control" value="<?php echo $_SESSION['company']->company_address?>" required/>
							</div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><?php echo get_phrase('phone');?></label>
							<div class="col-sm-10">
								<input name="phone" type="text" required class="form-control" value="<?php echo $_SESSION['company']->company_phone?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><?php echo get_phrase('timezone');?></label>
							<div class="col-sm-10">
									<select name="timezone" class="form-control">
									<?php for ($i=-12; $i<14;$i++) {?>
									<option value="<?php echo $i?>" <?php if ($i == $_SESSION['company']->timezone) echo "selected"?>> <?php if ($i>=0) echo "+"?><?php echo $i?>:00</option>
									<?php }?>
									</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label"><?php echo get_phrase('logo');?></label>
							<div class="col-sm-10">
								<?php if ($_SESSION['company']->profileimage!="") {?>
								<img class="img img-responsive " src="<?php echo base_url()?>/datas/logo/<?php echo $_SESSION['company']->profileimage?>" />
								
								<?php }?>
						    	<input type="file" class="form-control" id="logo" name="logo" placeholder="Logo Image"  value="">
						    	
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