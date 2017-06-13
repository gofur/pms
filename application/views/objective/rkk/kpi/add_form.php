<?php 
	$this->load->view('template/top_popup_1_view');
	$attributes = array('id' => 'genFrom', 'style'=>'min-height:600px'); 
?>
<h3>Key Performance Indicator</h3>
<?php echo form_open($process,$attributes,$hidden); ?>
<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span8"><?php echo $so->SasaranStrategis; ?></div>
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
	<div class="span2">Generic KPI</div>
	<div class="span8"><?php echo form_dropdown('slc_generic',$generic_ls, $generic,'id="slc_generic"'); ?></div>
</div>
<div id="div-generic">
	<div class="row">
		<div class="span2">KPI</div>
		<div class="span8"><?php echo form_input('txt_kpi', $kpi_text, 'id="txt_kpi" class="input-xlarge"'); ?></div>
	</div>
	<div class="row">
		<div class="span2">Description</div>
		<div class="span8"><?php echo form_textarea('txt_desc', $kpi_desc, 'id="txt_desc" class="input-xarge" rows="3"'); ?></div>
	</div>
	<div class="row">
		<div class="span2">Satuan</div>
		<div class="span8"><?php echo form_dropdown('slc_satuan',$satuan_ls, $satuan); ?></div>
	</div>

	<div class="row">
		<div class="span2">Formula</div>
		<div class="span8"><?php echo form_dropdown('slc_formula',$formula_ls, $formula); ?></div>
	</div>

	<div class="row">
		<div class="span2">Year to Date</div>
		<div class="span8"><?php echo form_dropdown('slc_ytd',$ytd_ls, $ytd); ?></div>
	</div>
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span8"><?php echo form_number('nm_weight', $weight, 'id="nm_weight" class="input-small" min="0" max="100" step="0.01"'); ?></div>
</div>
<div class="row">
	<div class="span2">Baseline</div>
	<div class="span8"><?php echo form_number('nm_base', $base, 'id="nm_base" class="input-small" step="0.01"'); ?></div>
</div>
<h3>Target</h3>
<div class="row">
	<div class="span2">Type</div>
	<div class="span8"><?php 
	$options = array('','Flat','Progressive');
	echo form_dropdown('slc_target_type', $options, '','id="slc_target_type" class="input-medium"');

	?></div>
</div>
<div class="row target-flat">
	<div class="span2">Def. Value</div>
	<div class="span8"><?php echo form_number('nm_def', '','id="nm_def" step="0.01" class="input-small"');?></div>
</div>
<div class="row target-progressive">
	<div class="span2">Start Value</div>
	<div class="span8"><?php echo form_number('nm_start', '','id="nm_start" step="0.01" class="input-small"');?></div>
</div>
<div class="row target-progressive">
	<div class="span2">Step Value</div>
	<div class="span8"><?php echo form_number('nm_step', '','id="nm_step" step="0.01" class="input-small"');?></div>
</div>
<div class="row target-flat target-progressive">
	<div class="span2">Check</div>
	<div class="span8">
	<?php
		$act_ls = array(
			0 => '', 
			1 => 'Monthly', 
			2 => 'Quarterly', 
			3 => 'every 4 Month', 
			4 => 'every 6 Month',
			5 => 'every Odd Month', 
			6 => 'every Even Month', 
		);
		echo form_dropdown('slc_action', $act_ls, '','id="slc_action" class="input-medium"');
	?>
	</div>
</div>

<div class="row">
	<div class="span10">
		<table class="table table-bordered" width="780px">
			<tbody>
				<?php 
				for ($y=0; $y < 3 ; $y++) { 
					echo '<tr>';
					for ($x=1; $x <=4 ; $x++) { 
						echo '<th colspan="2">'.date('M',mktime(0,0,0,($x+$y*4),1,2000)).'</th>';
					}
					echo '</tr>';
					echo '<tr>';
					for ($x=1; $x <=4 ; $x++) {
						$month_id  = $x+$y*4;
						$chk_class = 'chk_target';
						$nm_class  = 'input-small nm_target';

						if ($month_id % 2 == 0) {
							#genap
							$chk_class .= ' chk_even';
							$nm_class  .= ' nm_even';
						} else {
							#ganjil
							$chk_class .= ' chk_odd';
							$nm_class  .= ' nm_odd';
						}

						if ($month_id %3 == 0) {
							#triwulan
							$chk_class .= ' chk_tri';
							$nm_class  .= ' nm_tri';
						}

						if ($month_id %4 == 0) {
							#caturwulan
							$chk_class .= ' chk_catur';
							$nm_class  .= ' nm_catur';
						}

						if ($month_id %6 == 0) {
							#caturwulan
							$chk_class .= ' chk_semester';
							$nm_class  .= ' nm_semester';
						}

						if ($month_id < $min_month || $month_id > $max_month) {
							echo '<td>';
							echo form_checkbox('chk_months[]', $month_id, FALSE,'id="chk_month_'.$month_id.'" class="'.$chk_class.'" disable');
							echo '</td>';

							echo '<td>';
							echo form_number('nm_target_'.$month_id,$targets[$month_id],'id="nm_target_'.$month_id.'" class="'.$nm_class.'" step="0.01" disable');
							echo '</td>';
						} else {
							echo '<td>';
							echo form_checkbox('chk_months[]', $month_id, $months[$month_id],'id="chk_month_'.$month_id.'" class="'.$chk_class.'"');
							echo '</td>';

							echo '<td>';
							echo form_number('nm_target_'.$month_id,$targets[$month_id],'id="nm_target_'.$month_id.'" class="'.$nm_class.'" step="0.01"');
							echo '</td>';
						}

						
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		
	</div>
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
jQuery(document).ready(function($) {
	$('.target-flat').hide();
	$('.target-progressive').hide();
	$('#slc_target_type').change(function(event) {
		$('.target-flat').hide();
		$('.target-progressive').hide();
		$('#nm_def').val('');
		$('#nm_start').val('');
		$('#nm_step').val('');
		$('.chk_target').attr('checked', false);
		$('.nm_target').val('');
		$('#slc_action').val('');
		if ($(this).val()==1) {
			$('.target-flat').show();
		} else if ($(this).val()==2) {
			$('.target-progressive').show();
		};
	});
});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// $('#div-generic').hide();
	
	$('#slc_generic').change(function(event) {
		if ($(this).val()=='other') {
			$('#div-generic').show();
		}else {
			$('#div-generic').hide();
		}
	});

	$('#nm_def').change(function(event) {
		var def_val = $('#nm_def').val();
		var checked = $('#slc_action').val();
		$('.nm_target').val(''); 
		if (checked == 1) {
			$('.nm_target').val(def_val); 

		} else if (checked == 2) {
			$('.nm_tri').val(def_val); 

		} else if (checked == 3) {
			$('.nm_catur').val(def_val); 

		} else if (checked == 4) {
			$('.nm_semester').val(def_val); 

		} else if (checked == 5) {
			$('.nm_odd').val(def_val); 

		} else if (checked == 6) {
			$('.nm_even').val(def_val); 

		}
	});

	$('#slc_action').change(function(event) {
		var target_type = $('#slc_target_type').val();
		var def_val = $('#nm_def').val();
		var start_val = $('#nm_start').val();
		var step_val = $('#nm_step').val();
		$('.chk_target').attr('checked', false);
		$('.nm_target').val('');
		if (target_type == 1) {
			if ($(this).val() == 1) {
				$('.chk_target').attr('checked', true);
				$('.nm_target').val(def_val); 

			} else if ($(this).val() == 2) {
				$('.chk_tri').attr('checked', true);
				$('.nm_tri').val(def_val); 

			} else if ($(this).val() == 3) {
				$('.chk_catur').attr('checked', true);
				$('.nm_catur').val(def_val); 

			} else if ($(this).val() == 4) {
				$('.chk_semester').attr('checked', true);
				$('.nm_semester').val(def_val); 

			} else if ($(this).val() == 5) {
				$('.chk_odd').attr('checked', true);
				$('.nm_odd').val(def_val); 

			} else if ($(this).val() == 6) {
				$('.chk_even').attr('checked', true);
				$('.nm_even').val(def_val); 

			}
		} else if (target_type == 2) {
			if ($(this).val() == 1) {
				$('.chk_target').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					$('#nm_target_'+i).val(temp);
					temp = parseFloat(temp) + parseFloat(step_val);

				};
			} else if ($(this).val() == 2) {
				$('.chk_tri').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%3==0) {
						$('#nm_target_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);
						
					};

				};

			} else if ($(this).val() == 3) {
				$('.chk_catur').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%4==0) {
						$('#nm_target_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);
						
					};

				}; 

			} else if ($(this).val() == 4) {
				$('.chk_semester').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%6==0) {
						$('#nm_target_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);
						
					};

				}; 

			} else if ($(this).val() == 5) {
				$('.chk_odd').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%2==1) {
						$('#nm_target_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);
						
					};

				}; 

			} else if ($(this).val() == 6) {
				$('.chk_even').attr('checked', true);
				var temp = start_val;

				for (var i = 1; i <= 12; i++) {
					if (i%2==0) {
						$('#nm_target_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);
						
					};

				}; 

			}

		};
		
	});
});
</script>