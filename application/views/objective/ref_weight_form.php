<h3>Edit Ref. Weight</h3>
<?php $attributes = array('id' => 'genFrom'); 
echo form_open($process,$attributes)?>
<input type="hidden" name="hdn_RKKDetailID" id="hdn_RKKDetailID" value="<?php echo $old->RKKDetailID ?>">
<div class="row">
	<div class="span2">Ref. Weight</div>
	<div class="span3"><input type="text" class="input-small " name="txt_weight" id="txt_weight" value="<?php echo $old->Ref_weight ?>"> </div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>