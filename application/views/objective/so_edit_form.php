<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3>Stategic Objective</h3>
<input type="hidden" name="TxtRKKID" id="TxtRKKID" value="<?php echo isset($RKKID)?$RKKID:'' ?>">
<input type="hidden" name="TxtSOID" id="TxtSOID" value="<?php echo isset($old->SasaranStrategisID)?$old->SasaranStrategisID:'' ?>">
<input type="hidden" name="TxtOrgID" id="TxtOrgID" value="<?php echo isset($OrgID)?$OrgID:'' ?>">
<input type="hidden" name="hdn_perspective" id="hdn_perspective" value="<?php echo $Perspective->PerspectiveID ?>">
<div class="row">
	<div class="span2">Perspective</div>
	<div class="span10"><?php echo $Perspective->Perspective ?></div>
</div>

<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span3"><input type="text" name="TxtSO" id="TxtSO" value="<?php echo $old->SasaranStrategis ?>"></div>
	<div class="span5"><textarea name="TxtDesc" id="TxtDesc"s><?php echo $old->Description ?></textarea></div>
</div>


<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>