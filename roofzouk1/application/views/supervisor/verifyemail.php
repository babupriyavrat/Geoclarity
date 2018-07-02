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
                        	<li><b><?php echo get_phrase('verify_email');?></b></li>
                        </ul>
                    </div>
                </div>
                <!-- /END CONTAINER -->
            </div>
            <!-- /END STICKY NAVIGATION -->
	</header>
	
	<div class="container" style="margin-top:100px;">
        	<div class="row">
        		
        		<div class="col-md-8 col-md-offset-2">
        		<div class="panel panel-default">
        			<div class="panel-heading text-left" style="font-weight: bold"><?php echo get_phrase('verify_email');?></div>
        			<div class="panel-body">
                	<form name="loginfrm" id="loginfrm"  method="post" class="form-horizontal" role="form">
                	<input type="hidden" name="task" value="login" />
                		<?php if ($verifyError!="") {?>
						<div class="alert alert-danger">
							<?php echo $verifyError;?>
						</div>
						<?php }?>
						<?php if ($verifySuccess!="") {?>
						<div class="alert alert-success">
							<?php echo $verifySuccess;?>
						</div>
						
						<div class='form-group'>
							<input type="button" class="btn btn-primary" onclick="location.href='<?php echo base_url()?>index.php/supervisor/login'" value="<?php echo get_phrase('go_login');?>" >
						</div>
						<?php }?>
						 
					  
                	</form>
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
                                    message: 'Confirm Password must same value as Password'
                                }
                            }

                        }
                    }
                });
            });
            //]]>
        </script>