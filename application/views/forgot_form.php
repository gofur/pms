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
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom.css" type="text/css" media="screen"/>
<title><?php echo isset($pageTitle)?$pageTitle:'Performance Management System' ?></title>
</head>
<body class="login"> 
<div class="container-fluid container-center"> 
<div class="row">
	<div class="span7">
		<?php 
		switch ($notif) {
			case 1:
				echo '<div class="alert alert-block alert-success">';
	  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
	  		echo '<h4 class="alert-heading">Success !</h4>';
	  		echo '<center>Reset Password</center>';
				echo '</div>';
				break;
			case 2:
				echo '<div class="alert alert-block alert-error">';
	  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
	  		echo '<h4 class="alert-heading">Access Denied !</h4>';
	  		echo '<center>User does not exist </center>';
				echo '</div>';
				break;
			case 3:
				echo '<div class="alert alert-block alert-error">';
	  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
	  		echo '<h4 class="alert-heading">Access Denied !</h4>';
	  		echo '<center>Wrong email</center>';
				echo '</div>';
				break;
		}


		?>
	</div>
</div>

<div class="row">
	<div class="span7 title-login">
		<center><h3>PMS - Forgot Pass</h3></center>
	</div>
</div>

	<div class="row">
		<div class="span7">
			<?php
			$att = array('class' => 'well box-login', 'id' => 'loginForm');
			echo form_open('account/forgot_process',$att);

			?>
				<div class="row span2">
					<div class="span1">
						<?php
						$image_properties = array(
		          'src' => 'img/locked.png',
		          'width' => '142',
		          'height' => '147',
						);
						echo img($image_properties);
						?>
					</div>
				</div>
				<div class="row span2">
					<div class="span4">
						<label>NIK :</label> <input type="text" name="txtNIK" class="required" id="txtNIK" placeholder="NIK" maxlength="6">
					</div>
					<div class="span4">
						<label>Email :</label> <input type="email" class="required"  name="txtEmail" id="txtEmail" placeholder="email">
					</div>
					<div class="span4">
						<button type="submit" class="btn btn-primary">Send</button>
						<?php //echo anchor('account/login', 'Login', 'class="btn btn-link"'); ?>
					</div>
					<div class="span2">
					</div>
				</div>
			</form>
		</div> 
	</div>
</div>
<div class="row">
	<div class="span12">
<hr>
    <footer>
        <p class="span5 up">PM System ver.1.3 &copy; Corporate Human Resources - Kompas Gramedia 2012
			<br /> best view Firefox, Chrome, Internet Explorer 8+
        </p>
        <p class="footer-right"><?php echo img('img/footer.png')?>
        </p>
    </footer>
  </div>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-datepicker.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/dataTables-bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js" ></script>

<script type="text/javascript">
	
	$(document).ready(
		function()
		{
			$("#loginForm").validate(
			{
				rules: {
					txtNIK:{required:true},
					txtPass:{required:true}
				}
			})
		}
		)

</script>