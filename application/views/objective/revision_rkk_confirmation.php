
<?php
$attributes = array('class' => 'form-horizontal', 'id' => 'id_form_branch');
	echo form_open($action_confirmation, $attributes);
	?>
<div class="row">
	<div class="span6">
		<div class="row ">
			<div class="span11 alert alert-block">
			<?php
				$image_properties = array('src' => 'img/icon_confirm.png');
				echo img($image_properties);
			?> Are you sure want to revision this RKK & IDP ?
			<button type="submit" id="confirm_yes" value="1" name="confirm" class="btn btn-primary">Yes</button>
			<button type="submit" id="confirm_no" value="0" name="confirm" class="btn">No</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>