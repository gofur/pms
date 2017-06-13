
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
	<div class="span2">Begin</div>
	<div class="span8"><?php echo form_input('dt_begin', $begin, 'id="dt_begin" class="input-small datepicker"'); ?></div>
</div>

<div class="row">
	<div class="span2">End</div>
	<div class="span8"><?php echo form_input('dt_end', $end, 'id="dt_end" class="input-small datepicker"'); ?></div>
</div>

<div class="row">
	<div class="span2">SO Number</div>
	<div class="span8"><?php echo form_number('nm_so', 1, 'id="nm_so" class="input-small" min="1" min="10"'); ?></div>
</div>
<div id="div_field">

</div>

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
<script type="text/javascript">
	$(document).ready(function(){
		load_field();

		$('#nm_so').change(function(event) {
			if($.isNumeric($(this).val())){
				load_field();
			}
		});

		function load_field () {
			var base_url = '<?php echo base_url()?>'+'index.php/';
			$('#div_field').load( base_url+'objective/rkk/ajax_so_field/'+$('#nm_so').val());
		}
	});
</script>