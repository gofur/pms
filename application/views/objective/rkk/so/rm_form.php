
<?php 
	$this->load->view('template/top_popup_1_view');
	$attributes = array('id' => 'genFrom', 'style'=>'min-height:600px'); 
?>
<h3>Stategic Objective</h3>
<?php echo form_open($process,$attributes,$hidden); ?>
<div class="row">
	<div class="span2">Perspective</div>
	<div class="span8"><?php echo $persp->Perspective ?></div>
</div>
<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span8"><?php echo $so_text ?></div>

</div>
<div class="row">
	<div class="span2">Description </div>
	<div class="span8"><?php echo  $so_desc ?></div>
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
