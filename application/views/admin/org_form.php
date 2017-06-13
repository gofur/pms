<?php echo form_open($process,'id="genForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="<?php echo isset($old->OrganizationID)?$old->OrganizationID:''?>">
<input type="hidden" name="TxtOrganizationParent" id="TxtOrganizationParent" value="<?php echo isset($old->OrganizationParent)?$old->OrganizationParent:isset($head->OrganizationID)?$head->OrganizationID:'50002147'?>">

<div class="row">
	<div class="span2">Begin Date </div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date </div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Parent Organization</div>
	<div class="span10"><?php echo isset($head->OrganizationName)?$head->OrganizationName:'KOMPAS GRAMEDIA' ?></div>
</div>
<div class="row">
	<div class="span2">Organization Name</div>
	<div class="span10"><input type="text" class="input-large" name="TxtOrganizationName" id="TxtOrganizationName" value="<?php echo isset($old->OrganizationName)?$old->OrganizationName:''?>"></div>
</div>

<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>