<?php	$this->load->view('template/top_popup_1_view'); ?>
<h3>RKK Copy</h3>
<div class="row">
	<div class="span10">
		Select one to be Source and one to be Target Copy. <br>All KPI at Target's RKK will be <b>replaced</b> with KPI from Source's RKK. 
	</div>
</div>
<?php echo form_open($process, '', $hidden); ?>
<div class="row">
	<div class="span5">
		<h4><i class="icon-copy"></i> Source</h4>
		<!-- Source -->
		<?php
		foreach ($source_ls as $row) {

			echo '<label class="radio">';
			echo form_radio('rd_source', $row->RKKID, FALSE,'class="rd_source" data-nik="'.$row->NIK.'" data-post="'.$row->PositionID.'"');
			echo $source_lbl[$row->RKKID];

			echo '</label>';
	
		}
		?>
	</div>
	<div class="span5">
		<h4><i class="icon-paste"></i> Target</h4>
		<?php
		foreach ($target_ls as $row) {
			$key = $row->NIK.'|'. $row->PositionID.'|'. $row->isSAP;

			echo '<label class="radio">';
			echo '<input type="radio" class="rd_target" data-nik="'.$row->NIK.'" data-post="'.$row->PositionID.'" name="rd_target" id="rd_target_'.$row->NIK.'_'.$row->PositionID.'" value="'.$key .'">';
			echo $target_lbl[$key];
			echo '</label>';
			unset($key);
	
		}
		?>
	</div>
</div>
<div class="form-actions">
  <button type="submit" class="btn btn-primary">Submit</button>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('template/bottom_popup_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.rd_target').attr('disabled', true);
		$('.rd_source').change(function(event) {

			var nik = $(this).data('nik');
			var post = $(this).data('post');
			$('.rd_target').attr('disabled', false);
			$('#rd_target_'+nik+'_'+post).attr('disabled', true);
		});
	});
</script>