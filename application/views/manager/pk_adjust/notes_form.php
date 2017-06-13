<?php 
	$this->load->view('template/top_popup_1_view', FALSE);
?>
<div class="row">
	<div class="span10">
		<h3>Adjustment Notes</h3>
	</div>
</div>
<?php echo form_open('manager/pk_adjust/notes_save', '', $hidden); ?>
<div class="row">
	<div class="span8">
	<?php echo form_textarea('txt_notes', $notes, 'class="input-xlarge" rows="5"'); ?>
	</div>
</div>

<div class="row">
	<div class="span8">
	<?php echo form_submit('btn_submit', 'Save','class="btn"'); ?>
	</div>
</div>
<?php echo form_close(); ?>

<?php 
	$this->load->view('template/bottom_popup_1_view', FALSE);
?>