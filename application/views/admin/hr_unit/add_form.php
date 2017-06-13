<?php $this->load->view('template/top_1_view'); ?>

<h3>Add HR Unit</h3>
<?php echo form_open('admin/hr_unit/add_process', '', $hidden); ?>
<div class="row">
	<div class="span2">NIK</div>
	<div class="span2"><?php echo form_input('txt_nik', '', 'class="input-small"'); ?></div>
</div>
<!-- <div class="row">
	<div class="span2">NIK</div>
	<div class="span4"><?php echo form_input('txt_name', '', 'class="input-large" readonly'); ?></div>
</div> -->
<div class="row">
	<div class="span4 offset2"><?php echo form_submit('btn_submit', 'Submit','class="btn"');?></div>
</div>
<?php echo form_close()?>
<?php $this->load->view('template/bottom_1_view'); ?>
