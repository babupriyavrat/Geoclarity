<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
	<title><?php echo get_phrase('company_dashboard');?></title>
    
    <link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico">
    
  <link rel="stylesheet" href="<?php echo base_url()?>css/bootstrap.min.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/icons/icons.css"/>
  <link rel="stylesheet" href="<?php echo base_url()?>css/styles.css?version=1.2"/>
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
						 <?php if ($_SESSION['company']->profileimage!="") {?>
                        <a class="navbar-brand" href="#" style="padding-top:7px;">
								<img  src="<?php echo base_url()?>/datas/logo/<?php echo $_SESSION['company']->profileimage?>" style="height: 60px;"/>
						</a>		
						<?php }?>
                        <!-- LOGO -->
                        <a class="navbar-brand" href="<?php echo base_url()?>index.php/company/home">
                        	
                            <!--<img src="images/logo-nav.png" alt=""> -->
                        </a>
						<ul class="nav navbar-nav navbar-left main-navigation small-text">
                            <li><a href="<?php echo base_url()?>index.php/company/home" style="font-size:17px;font-weight: bold"><?php echo get_phrase('company_dashboard');?></a></li>
                        </ul>
                    </div>

                    <!-- TOP BAR -->
                    <div class="navbar-collapse collapse" id="stamp-navigation">
                        <!-- NAVIGATION LINK -->
                        <!-- NAVIGATION LINK -->
                        <ul class="nav navbar-nav navbar-left main-navigation small-text">
                        </ul>
                        <!-- LOGIN REGISTER -->
                        
                        <ul class="nav navbar-nav navbar-right main-navigation  small-text" >
                       		<li>
                       			<form method="get" action="<?php echo base_url()?>index.php/company/search">
                       				<input name="q" type="text" class="form-control" placeholder="<?php echo get_phrase('search');?>..." style="width:150px;float:left;margin-top:20px;" value="<?php echo isset($_REQUEST['q']) ? $_REQUEST['q'] : ""?>">
                       			</form>
                       		</li> 	
                        	<li><a href="<?php echo base_url()?>index.php/company/upload"><?php echo get_phrase('upload');?></a></li>
           					<li><a href="<?php echo base_url()?>index.php/company/profile"><?php echo get_phrase('profile');?></a></li>
           					
           					<li>
           					    <?php
           					        $lang = $this->session->userdata('language');
           					        if ($lang == ''){
           					            $lang = 'english';
           					        }
           					    ?>
           					    <form id="languageForm" method="post" action="<?php echo base_url()?>index.php/multilanguage/select_language">
           					        <input type="hidden" name="redirectURL" value="<?php echo uri_string();?>">
               					    <select id="language" name="language" class="form-control" style="width:120px;float:left;margin-top:20px;">
               					        <option value="english" <?php if ($lang=='english') echo 'selected'?>>English</option>
               					        <option value="french" <?php if ($lang=='french') echo 'selected'?>>Français</option>
               					        <option value="spanish" <?php if ($lang=='spanish') echo 'selected'?>>Español</option>
               					        <option value="chinese" <?php if ($lang=='chinese') echo 'selected'?>>中文</option>
               					        <option value="malay" <?php if ($lang=='malay') echo 'selected'?>>Melayu</option>
               					    </select>
           					    </form>
           					</li>
           					
		                	<li><a href="<?php echo base_url()?>index.php/company/logout"><?php echo get_phrase('log_out');?> (<?php echo $_SESSION['company']->company_name?>)</a></li>
		                	
                        </ul>
                    </div>
                </div>
                <!-- /END CONTAINER -->
            </div>
            <!-- /END STICKY NAVIGATION -->
	</header>
	
<script type="text/javascript">
    $("#language").change(function(){
        $('#languageForm').submit();
    });
</script>
