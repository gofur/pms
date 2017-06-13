<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>Achievement</h3>
<?php echo form_open_multipart($process, '', $hidden); ?>
<div class="row">
	<div class="span2">SO</div>
	<div class="span8"><?php echo $kpi->SasaranStrategis ?></div>
</div>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi->KPI ?></div>
</div>
<div class="row">
	<div class="span2"><?php echo $month ?>'s Target</div>
	<div class="span8"><?php echo $target .' '.$kpi->Satuan ?></div>
</div>
<div class="row">
	<div class="span2">Achievement</div>
	<div class="span2"><?php echo form_number('nm_achv',$achv,'class="input-small" id="nm_achv" step="0.01"') ?></div>
	<?php 
		if ($btn_calc) {
			echo '<div class="span1"><a href="#" class="btn" id="btn_calc" title="Calculated Achv"><i class="icon icon-beaker"></i></a></div>';
		} 
	?>
	
	<div class="span2"> 
		<label class="checkbox">
			<?php echo form_checkbox('chk_skip', 1, $skip ,'id="chk_skip"');?>
			Skip
    </label>
  </div>
</div>
<div class="row">
	<div class="span2">Evidence</div>
	<div class="span8">
		<input type="file" name="fl_evidence">
		<?php
		if ($evid!=''){
			echo '<a title="Download Eviedence" href="http://'.$_SERVER['HTTP_HOST'].$evid.'"><i class="icon-paperclip"></i></a>';
		}
		?>
	</div>
</div>
<div class="row">
	<div class="span2">Notes</div>
	<div class="span8">
		<?php echo form_textarea('txt_note', $note, 'id="txt_note" class="input-xlarge"'); ?>
	</div>
</div>

<div class="row">
	<div class="span10">
		<div class="form-actions">
			<?php
				// if ($disable == TRUE) {
				// 	echo anchor('#', 'Save', 'class="btn btn-primary disabled');
				// } else {
					echo form_submit('btn_submit', 'Save','class="btn btn-primary"');
				// }
			?>
		 
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('template/bottom_popup_1_view'); ?>

<script>
	jQuery(document).ready(function($) {
		$('#btn_calc').click(function(event) {
			$.ajax({
				url: "<?php echo base_url().'index.php/performance/achievement/recalc_achv'?>",
				type: 'POST',
				data: {kpi_id: $('#kpi_id').val()},
			})
			.done(function(result) {
				$('#nm_achv').val(result);
				console.log("success");

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		});
	});
</script>