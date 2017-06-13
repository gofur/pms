<?php $this->load->view('template/top_1_view'); ?>
<h3>Create Self RKK & IDP</h3>
<?php $this->load->view('template/header_1_view'); ?>
<hr>
<?php 
	if (isset( $notif_text) && $notif_text!=''){
		$this->load->view('template/notif_1_view');
	}
?>

<?php echo form_open($action_process,'',$hidden) ?>
<div class="row">
	<div class="span2">RKK & IDP Start</div>
	<div class="span4"><input type="text" name="dt_start"  id="dt_start" value="<?php echo substr($period->BeginDate, 0,10); ?>" class="input-small"></div>
</div>
<div class="row">
	<div class="span2">RKK & IDP End</div>
	<div class="span4"><input type="text" name="dt_end"  id="dt_end" value="<?php echo substr($period->EndDate, 0,10); ?>" class="input-small"></div>
</div>
<div class="form-actions">
  <button type="submit" class="btn btn-primary">Create</button>

</div>
<?php echo form_close(); ?>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "#dt_start" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#dt_end" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>