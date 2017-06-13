<?php echo form_open($process,'id="scaleForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtCodeColourID" id="TxtCodeColourID" value="<?php echo isset($old->CodeColourID)?$old->CodeColourID:''?>">

<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Color :</div>
	<div class="span10"><input type="text"   class="input-small izzyColor" name="TxtColor" id="TxtColor" value="<?php echo isset($old->Colour)?$old->Colour:''?>"> </div>
</div>
<div class="row">
	<div class="span2">Score :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtPAScore" id="TxtPAScore" value="<?php echo isset($old->PAScore)?$old->PAScore:''?>"> </div>
</div>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>