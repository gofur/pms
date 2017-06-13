<?php $this->load->view('template/top_1_view'); ?>
<h3>Project</h3>
<?php echo form_open($action, '', $hidden); ?>
<div class="row">
	<div class="span3">Project Title</div>
	<div class="span9"><?php echo form_input('txt_title', $title, 'class="input-medium"');?></div>
</div>
<div class="row">
	<div class="span3">Document Number</div>
	<div class="span9"><?php echo form_input('txt_doc', $title, 'class="input-medium"');?></div>
</div>
<div class="row">
	<div class="span3">Description</div>
	<div class="span9"><?php echo form_textarea('txt_desc', $desc, 'class="input-xlarge" rows="3"');?></div>
</div>
<div class="row">
	<div class="span3">Start</div>
	<div class="span9"><?php echo form_input('dt_start', $start, 'class="datepicker input-small"');?></div>
</div>
<div class="row">
	<div class="span3">End</div>
	<div class="span9"><?php echo form_input('dt_end', $end, 'class="datepicker input-small"');?></div>
</div>
<div class="row">
	<div class="span3">Scope</div>
	<div class="span9"><?php echo form_dropdown('slc_scope', $scope_list, $scope);?></div>
</div>
<hr>
<div class="row">
	<div class="span3">Project Leader</div>
	<div class="span9">
		<?php echo form_input('txt_nik', '', 'class="input-small" id="txt_nik"');?>
		<?php echo form_input('txt_name', '', 'class="input-large" readonly="readonly" id="txt_name"');?>

	</div>
</div>


<?php echo form_submit('btn_submit', 'Save','class="btn btn-primary"'); ?>
<?php echo form_close();?>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo site_url() ?>';
		$('.datepicker').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$('#txt_nik').focusout(function(event) {
			if ($(this).val().length==6) {
								
				$.ajax({
					url: base_url+'/misc/project/nik_to_name/',
					type: 'POST',
					data: {nik: $(this).val()},
				})
				.done(function(msg) {
					$('#txt_name').val(msg);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {

				});
			};
		});
	});
</script>