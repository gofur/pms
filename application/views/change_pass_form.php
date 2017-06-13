<?php if($notif!=''){ echo '<div class="alert '.$notif_type.'">'.$notif.'</div>';} ?>
<?php echo form_open('account/change_pass_process','id="changePasswordForm"');?>
<!--<div class="row">
	<div class="span3">Current Password</div>
	<div class="span3"><input type="password" class="required" name="TxtOldPassword" id="TxtOldPassword"> </div>
</div>-->
<h3><?php echo $title ?></h3>
<div class="row">
	<div class="span3">New Password</div>
	<div class="span3">
		<input type="text" class="required" name="TxtNewPassword" id="TxtNewPassword"> 
	</div>
</div>
<div class="row">
	<div class="span3">Repeat New Password</div>
	<div class="span3">
		<input type="text" class="required" name="TxtReNewPassword" id="TxtReNewPassword"> 
	</div>
</div>
<div class="row">
	<div class="span3 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn"'); ?>
	</div>
</div>
<?php form_close();?>