<?php echo form_open($process,'id="periodForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="txt_layer_id" id="txt_layer_id" value="<?php echo isset($old->layer_id)?$old->layer_id:''?>">
<?php if($do_act=='edit' || $do_act=='add') { ?>
<div class="row">
	<div class="span2">Label :</div>
	<div class="span10"><input type="text" <?php echo isset($old->label)?$old->label:''?> class="input-large" length="5" name="txt_label" id="txt_label" value="<?php echo isset($old->label)?$old->label:''?>"> </div>
</div>

<div class="row">
	<div class="span2">ESG :</div>
	<div class="span10"><input type="text" <?php echo isset($old->label)?$old->label:''?> class="input-large" length="5" name="txt_esg" id="txt_label" value="<?php echo isset($old->esg)?$old->esg:''?>"> </div>
</div>

<div class="row">
	<div class="span2">Description :</div>
	<div class="span10" ><textarea name="txt_description" id="txt_description"><?php echo isset($old->description)?$old->description:''?></textarea></div>
</div>
<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->begin_date)?'':''?> class="input-small" name="txt_begin_date" id="txt_begin_date" value="<?php echo isset($old->begin_date)?substr($old->begin_date,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="txt_end_date" id="txt_end_date" value="<?php echo isset($old->end_date)?substr($old->end_date,0,10) :'9999/12/31'?>"> </div>
</div>

<?php }else{ ?>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="txt_end_date" id="txt_end_date" value="<?php echo isset($old->end_date)?substr($old->end_date,0,10) :'9999/12/31'?>"> </div>
</div>
<?php } ?>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php echo form_close();?>