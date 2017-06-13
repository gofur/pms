<?php echo form_open($process,'id="objectiveFrom"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtObjectiveID" id="TxtObjectiveID" value="<?php echo isset($oldObjective->SasaranStrategisID)?$oldObjective->SasaranStrategisID:''?>">
<input type="hidden" name="TxtPerspectiveID" id="TxtPerspectiveID" value="<?php echo isset($idPerspective)?$idPerspective:$oldObjective->PerspectiveID ?>">
<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="<?php echo isset($OrganizationID)?$OrganizationID:$oldObjective->OrganizationID?>">
<div class="row">
	<div class="span1">Objective:</div>
	<div class="span11"><input type="text" class="input-large" name="TxtObjective" id="TxtObjective" value="<?php echo isset($oldObjective->SasaranStrategis)?$oldObjective->SasaranStrategis:''?>"> </div>
</div>
<div class="row">
	<div class="span1">Description:</div>
	<div class="span11"><input type="text" class="input-large" name="TxtDescription" id="TxtDescription" value="<?php echo isset($oldObjective->Description)?$oldObjective->Description:''?>"> </div>
</div>

<div class="row">
	<div class="span11 offset1">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>