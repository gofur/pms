<?php $this->load->view('template/top_1_view'); ?>
<h3>Member Project</h3>
<?php echo form_open($action, '', $hidden); ?>
<div class="row">
	<div class="span2">NIK</div>
	<div class="span8"><?php echo form_input('txt_nik', $nik, 'class="input-small" id="txt_nik"');?>
	<?php echo form_input('txt_name','', 'class="input-xlarge" readonly="readonly" id="txt_name"');?></div>
</div> 
<div class="row">
	<div class="span2">KPI</div>
	<div class="span10"><?php echo form_input('txt_kpi', $kpi, 'class="input-large"');?></div>
</div>
<div class="row">
	<div class="span2">Role</div>
	<div class="span10"><?php echo form_input('txt_role', $role, 'class="input-large"');?></div>
</div>
<div class="row">
	<div class="span2">Result</div>
	<div class="span10"><?php echo form_number('nm_result', $result, 'class="input-small" min=0 min=1 step=0.01');?></div>
</div>
<?php echo form_submit('btn_submit', 'Save','class="btn btn-primary"'); ?>
<?php echo form_close();?>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var base_url = '<?php echo base_url(); ?>'+'index.php/';
	$.ajax({
		url: base_url + 'misc/project/nik_to_name',
		type: 'POST',
		data: {nik: $('#txt_nik').val()},
	})
	.done(function(msg) {
		$('#txt_name').val(msg);
	});

	$('#txt_nik').focusout(function(event) {
		if ($(this).val().length==6) {
			$.ajax({
				url: base_url+'misc/project/nik_to_name/',
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