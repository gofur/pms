<h3>Link KPI</h3>
<?php $attributes = array('id' => 'genFrom'); echo $action ?>

<input type="hidden" name="hdn_rkkDetail" id="" value="<?php echo  $KPI_head->RKKDetailID ?>">

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $KPI_head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">Chief's KPI</div>
	<div class="span8"><?php echo form_dropdown('slc_kpi',$kpi_opt,$kpi_slc,'class="input-xlarge"') ?></div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary <?php echo $disabled ?>">Link</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>