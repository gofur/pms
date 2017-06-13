<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<link href="<?php echo base_url();?>img/favicon.gif" rel="shortcut icon"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.fancybox.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/jqx.base.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom-theme/jquery-ui-1.8.16.custom.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom-theme/jquery.ui.1.8.16.ie.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/DT_bootstrap.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome/css/font-awesome.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrapSwitch.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/sweetalert/sweetalert.css" type="text/css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/icon.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom.css" type="text/css" media="screen"/>
<title><?php echo isset($pageTitle)?$pageTitle:'Performance Management System' ?></title>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<?php echo anchor('home','Home','class="brand"'); ?>
				<div class="nav-collapse collapse">
					<?php echo build_menu(0,0,$result='') ?>
					<ul class="nav pull-right">
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello, <?php echo $this->session->userdata('username'); ?><b class="caret"></b></a><ul class="dropdown-menu">
							<li><?php
							//if (!$this->session->userdata('isSAP')){
								echo anchor('account/change_pass', 'Change Password');
							//}
							?></li>
							<li><?php echo anchor('account/logout', 'Logout');?></li>
						</ul></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<div class="banner">
		<img src="<?php echo base_url()?>/img/banner.png">
		<!--
		<div class="doodle">
			<img src="<?php echo base_url()?>/img/logo-default.png">
		</div>
		<div class="animation">
			<embed src="<?php echo base_url()?>/img/header.swf" quality="high" wmode="transparent" bgcolor="#ffffff" name="hr portal b" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="100%" width="100%">
		</div>
		-->
</div>

<div class="container">
