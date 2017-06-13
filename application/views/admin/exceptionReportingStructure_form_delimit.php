<div class="row">
	<div class="span12">
		<?php echo form_open($process,'id="exceptionFrom"');?>
		<h3><?php echo $title ?></h3>
	</div>
</div>
<?php echo form_hidden('hdn_id', $old->ExceptionReportingStructureID);?>
<div class="row">
	<div class="span2">Start Date</div>
	<div class="span10"><?php echo substr($old->BeginDate, 0,10) ;?></div>

</div>
<div class="row">
	<div class="span2">End Date</div>
	<div class="span10"><?php echo form_input('TxtEndDate', substr($old->EndDate, 0,10), 'class="input-small" id="TxtEndDate"');?></div>

</div>
<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span1"><h4>Superior</h4></div>
			<div class="span5"></div>
		</div>
		<div class="row">
			<div class="span2">Unit</div>
			<div class="span3"><?php echo $org_sup?></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span3"><?php echo $position_sup?></div>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<div class="span1"><h4>Subordinate</h4></div>
			<div class="span5"></div>
		</div>
		<div class="row">
			<div class="span2">Unit</div>
			<div class="span3"><?php echo $org_sub?></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span3"><?php echo $position_sub?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('admin/exceptionReportingStructure','Cancel', 'class="btn"'); ?>
	</div>
</div>
<?php echo form_close() ?>