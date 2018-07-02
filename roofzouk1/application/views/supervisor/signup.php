<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
	<title><?php echo get_phrase('system_title');?></title>
    
    <link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico">
    
  <link rel="stylesheet" href="<?php echo base_url()?>css/bootstrap.min.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/icons/icons.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/styles.css?version=1.1"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/responsive.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/colors/blue.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/dataTables.bootstrap.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/datepicker.min.css"/>
  
  <script src="<?php echo base_url()?>js/jquery.min.js"></script>
  <script src="<?php echo base_url()?>js/bootstrap.min.js"></script>
	
<script src="<?php echo base_url()?>js/jquery.nav.js"></script>
    
<script src="<?php echo base_url()?>js/validate/formValidation.min.js"></script>
<script src="<?php echo base_url()?>js/validate/framework/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/common.js"></script>

<body>
<div class="wrap">
	<div id="main">
		<header class="header" data-stellar-background-ratio="0.5" id="home">
	<!-- STICKY NAVIGATION -->
            <div class="navbar navbar-inverse bs-docs-nav navbar-fixed-top sticky-navigation" role="navigation">
                <div class="container">
                    <div class="navbar-header">

                        <!-- LOGO ON STICKY NAV BAR -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#stamp-navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-grid-2x2"></span>
                        </button>

                        <!-- LOGO -->
                        <a class="navbar-brand" href="#">
                        	
                            <!--<img src="images/logo-nav.png" alt=""> -->
                        </a>
						<ul class="nav navbar-nav navbar-left main-navigation small-text">
                            <li><a href="#" style="font-size:17px;font-weight: bold"><?php echo get_phrase('supervisor_dashboard');?></a></li>
                        </ul>
                    </div>

                    <!-- TOP BAR -->
                    <div class="navbar-collapse collapse" id="stamp-navigation">
                        <!-- NAVIGATION LINK -->
                        <!-- NAVIGATION LINK -->
                        <ul class="nav navbar-nav navbar-left main-navigation small-text">
                        </ul>
                        <!-- LOGIN REGISTER -->
                        <ul class="nav navbar-nav navbar-right main-navigation  small-text" style="margin-top:20px;margin-right:10px;">
                        	<li>
           					    <?php
           					        $lang = $this->session->userdata('language');
           					        if ($lang == ''){
           					            $lang = 'english';
           					        }
           					    ?>
           					    <form id="languageForm" method="post" action="<?php echo base_url()?>index.php/multilanguage/select_language">
           					        <input type="hidden" name="redirectURL" value="<?php echo uri_string();?>">
               					    <select id="language" name="language" class="form-control" style="width:120px;float:left;margin-right:20px;">
               					        <option value="english" <?php if ($lang=='english') echo 'selected'?>>English</option>
               					        <option value="french" <?php if ($lang=='french') echo 'selected'?>>Français</option>
               					        <option value="spanish" <?php if ($lang=='spanish') echo 'selected'?>>Español</option>
               					        <option value="chinese" <?php if ($lang=='chinese') echo 'selected'?>>中文</option>
               					        <option value="malay" <?php if ($lang=='malay') echo 'selected'?>>Melayu</option>
               					    </select>
           					    </form>
           					</li>
                        	<li><b><?php echo get_phrase('sign_in');?> <?php echo get_phrase('or');?> <?php echo get_phrase('sign_up');?></b></li>
                        </ul>
                    </div>
                </div>
                <!-- /END CONTAINER -->
            </div>
            <!-- /END STICKY NAVIGATION -->
	</header>
	
	<div class="container" style="margin-top:100px;">
        	<div class="row">
        		<div class="col-md-6 col-md-offset-3" >
                	<div class="panel panel-default">
        			<div class="panel-heading text-left" style="font-weight: bold"><?php echo get_phrase('sign_up');?></div>
        			<div class="panel-body">
                	<form name="registerfrm" id="registerfrm" method="post" class="form-horizontal">
                	
	                	<?php if ($registerError!="") {?>
						<div class="alert alert-danger">
							<?php echo $registerError;?>
						</div>
						<?php }?>
						<?php if ($registerSuccess!="") {?>
						<div class="alert alert-success">
							<?php echo $registerSuccess;?>
						</div>
						<?php }?>
						
						<?php if ($NotFoundCompany !="") {?>
						<input type="hidden" name="task" value="company" />
						<div class="form-group">
							<label class="col-sm-12  text-left">Please input your contact information. We will contact you soon.</label>
							<div class="col-sm-12">
						    	<textarea type="text" class="form-control" id="contact" name="contact" placeholder="Contact Information" required value=""></textarea>
						    </div>
						</div>
						<?php } else {?>
						<input type="hidden" name="task" value="register" />
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('supervisor_email');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="email" name="email" placeholder="Supervisor EMAIL" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('password');?></label>
							<div class="col-sm-8">
						    	<input type="password" class="form-control" id="pwd" name="pwd" placeholder="<?php echo get_phrase('password');?>" required>
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('confirm_password');?></label>
							<div class="col-sm-8">
						    	<input type="password" class="form-control" id="confirmpwd" name="confirmpwd" placeholder="<?php echo get_phrase('confirm_password');?>" required>
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('supervisor_name');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="Supervisorname" name="Supervisorname" placeholder="Supervisor NAME" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('department');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="department" name="department" placeholder="Department" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('teamsize');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id=""teamsize"" name="teamsize" placeholder="Team Size" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('home_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="homePhone" name="homePhone" placeholder="Home Phone #" required value="">
						    </div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('office_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="officePhone" name="officePhone" placeholder="Office Phone #" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('mobile_phone');?> #</label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="mobilephone" name="mobilephone" placeholder="Mobile Phone #" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('supervisor_address');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="address" name="address" placeholder="Supervisor ADDRESS" required value="">
						    </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo get_phrase('company_name');?></label>
							<div class="col-sm-8">
						    	<input type="text" class="form-control" id="companyName" name="companyName" placeholder="Company Name" required value="">
						    </div>
						</div>
						
						<?php }?>
						<div class="form-group">
						 	<div class="col-sm-12">
						 	<input type="submit" class="btn standard-button" value="<?php echo get_phrase('submit');?>" />
							</div>
						</div>
                	</form>
                	
                	<div class="row">
                		<div class="col-sm-6">
                			<a href='forgotpassword'><?php echo get_phrase('forgot_password?');?></a>
                		</div>
                		<div class="col-sm-6">
                			<a href='login'><?php echo get_phrase('sign_in');?></a>
                		</div>
                	</div>
                </div>
        	</div>
        </div>
        
</div>
<script type="text/javascript">
            //<![CDATA[
            //var dataForm = new RegisternForm('form-validate', true);
            $(document).ready(function () {
            	$("#language").change(function(){
                    $('#languageForm').submit();
                });
                
                $('#loginfrm').formValidation({
                    framework: 'bootstrap',
                    icon: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                    }
                });

                $('#registerfrm').formValidation({
                    framework: 'bootstrap',
                    icon: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                    	email: {
                            validators: {
                                emailAddress: {
                                }
                            }
                        }, 
                        confirmpwd: {
                            validators: {
                                identical: {
                                    field: 'pwd',
                                    message: '<?php echo get_phrase('confirm_password_mismatch');?>'
                                }
                            }

                        }
                    }
                });
            });
            //]]>
        </script>