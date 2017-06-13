<?php echo form_open($process,'id="periodForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtPeriodID" id="TxtPeriodID" value="<?php echo isset($old->PeriodePMID)?$old->PeriodePMID:''?>">
<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Period :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtYear" id="TxtYear" value="<?php echo isset($old->Tahun)?$old->Tahun:''?>"> </div>
</div>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>