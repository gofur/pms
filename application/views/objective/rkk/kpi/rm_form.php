
<?php 
	$this->load->view('template/top_popup_1_view');
	$attributes = array('id' => 'genFrom', 'style'=>'min-height:600px'); 
?>
<h3>Key Performance Indicator</h3>
<?php echo form_open($process,$attributes,$hidden); ?>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi_text ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $kpi_desc ?></div>

</div>

<?php $this->load->view('template/rm_form'); ?>

<div class="row">
	<div class="span10">
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Save</button>
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>

</form>
<?php
	$this->load->view('template/bottom_popup_1_view');
?>
