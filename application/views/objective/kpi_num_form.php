<h3>Create KPI</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li class="active">Number<span class="divider"> / </span></li>
		  <li >Detail<span class="divider"> / </span></li>
		  <li >Target</li>
	  </ul>
	</div>
</div>
<?php 
$attributes = array('id' => 'genFrom'); 
echo form_open($process,$attributes)
?>
<input type="hidden" name="hdn_rkk_id" id="hdn_rkk_id" value="<?php echo $rkk_id; ?>">
<input type="hidden" name="hdn_rkk_position_id" id="hdn_rkk_position_id" value="<?php echo $rkk_position_id; ?>">
<input type="hidden" name="hdn_so_id" id="hdn_so_id" value="<?php echo $so_id; ?>">
<div class="row">
	<div class="span2">KPI Number</div>
	<div class="span8"><input type="text" class="input-small  numeric-integer" name="txt_num" id="txt_num" value="1" min="1" max="10"></div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>