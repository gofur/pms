<hr>
<b>Cascading #<?php echo $num; ?></b>
<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">Cascade to</div>
			<div class="span3"><?php echo form_dropdown('slc_subd_'.$num, $subd_ls, '','class="input-xlarge" id="slc_subd_'.$num.'"'); ?></div>
		</div>
		<div class="row" class="ref_weight">
			<div class="span2">Begin</div>
			<div class="span3"><?php echo form_input('dt_begin_'.$num, '', 'id="dt_begin_'.$num.'" class="input-small datepicker" '); ?></div>
		</div>
		<div class="row" class="ref_weight">
			<div class="span2">End</div>
			<div class="span3"><?php echo form_input('dt_end_'.$num, '', 'id="dt_end_'.$num.'" class="input-small datepicker" '); ?></div>
		</div>
		<div class="row" class="ref_weight">
			<div class="span2">Ref. Weight</div>
			<div class="span3"><?php echo form_number('nm_ref_weight_'.$num, 0, 'id="nm_ref_weight_'.$num.'" class="input-small nm_ref_weight" min="0" max="100" step="0.01"'); ?></div>
		</div>
		<div class="row">
			<div class="span2">KPI</div>
			<div class="span3"><?php echo form_input('txt_kpi_'.$num, '','class="input-xlarge txt_kpi" id="txt_kpi_'.$num.'"'); ?></div>
		</div>

		<div class="row">
			<div class="span2">Description</div>
			<div class="span3"><?php echo form_textarea('txt_desc_'.$num, '','class="input-xlarge txt_desc" rows="3" id="txt_desc_'.$num.'"'); ?></div>
		</div>

		<div class="row">
			<div class="span2">Weight</div>
			<div class="span3"><?php echo form_number('nm_weight_'.$num, 0, 'id="nm_weight_'.$num.'" class="input-small" min="0" max="100" step="0.01"'); ?></div>
		</div>
		<div class="row">
			<div class="span2">Baseline</div>
			<div class="span3"><?php echo form_number('nm_base_'.$num, 0, 'id="nm_base_'.$num.'" class="input-small" step="0.01"'); ?></div>
		</div>
		<hr>
		<div class="row">
			<div class="span2">Target Type</div>
			<div class="span3"><?php
			$options = array('','Flat','Progressive');
			echo form_dropdown('slc_target_type_'.$num.'', $options, '','id="slc_target_type_'.$num.'" class="input-medium"');

			?></div>
		</div>
		<div class="row target-flat_<?php echo $num ?>">
			<div class="span2">Def. Value</div>
			<div class="span3"><?php echo form_number('nm_def_'.$num.'', '','id="nm_def_'.$num.'" step="0.01" class="input-small"');?></div>
		</div>
		<div class="row target-progressive_<?php echo $num ?>">
			<div class="span2">Start Value</div>
			<div class="span3"><?php echo form_number('nm_start_'.$num.'', '','id="nm_start_'.$num.'" step="0.01" class="input-small"');?></div>
		</div>
		<div class="row target-progressive_<?php echo $num ?>">
			<div class="span2">Step Value</div>
			<div class="span3"><?php echo form_number('nm_step_'.$num.'', '','id="nm_step_'.$num.'" step="0.01" class="input-small"');?></div>
		</div>
		<div class="row">
			<div class="span2">Check</div>
			<div class="span3">
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
				echo form_dropdown('slc_action_'.$num, $act_ls, '','id="slc_action_'.$num.'"');
			?>
			</div>
		</div>
	</div>
	<div class="span4">
		<table class="table table-bordered">
			<tbody>
				<?php
				for ($month_id=1; $month_id <=12 ; $month_id++) {
					$chk_class = 'chk_target_'.$num .' chk_target_B_'.$month_id;
					$nm_class  = 'input-small nm_target_'.$num.' nm_target_B_'.$month_id;
					if ($month_id % 2 == 0) {
						#genap
						$chk_class .= ' chk_even_'.$num;
						$nm_class  .= ' nm_even_'.$num;
					} else {
						#ganjil
						$chk_class .= ' chk_odd_'.$num;
						$nm_class  .= ' nm_odd_'.$num;
					}

					if ($month_id %3 == 0) {
						#triwulan
						$chk_class .= ' chk_tri_'.$num;
						$nm_class  .= ' nm_tri_'.$num;
					}

					if ($month_id %4 == 0) {
						#caturwulan
						$chk_class .= ' chk_catur_'.$num;
						$nm_class  .= ' nm_catur_'.$num;
					}

					if ($month_id %6 == 0) {
						#caturwulan
						$chk_class .= ' chk_semester_'.$num;
						$nm_class  .= ' nm_semester_'.$num;
					}

					echo '<tr>';
					echo '<td>'.date('M',mktime(0,0,0,$month_id,1,2000)).'</td>';
					echo '<td>';
					echo form_checkbox('chk_month_'.$num.'[]', $month_id, FALSE,'id="chk_month_'.$num.'_'.$month_id.'" class="'.$chk_class.'"');
					echo '</td>';
					echo '<td>';
						echo form_number('nm_target_'.$num.'_'.$month_id,'','id="nm_target_'.$num.'_'.$month_id.'" class="'.$nm_class.'" step="0.01"');
						echo '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".datepicker").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});

		$('#slc_subd_'+'<?php echo $num; ?>').change(function(event) {
			var base_url = '<?php echo base_url()?>'+'index.php/';
			$.ajax({
				url: base_url+'objective/rkk/ajax_cascade_date',
				type: 'POST',
				data: {rkk_id: $(this).val()},
			})
			.done(function(msg) {
				var begin = msg.substr(1, 10);
				var end = msg.substr(12, 10);
				$('#dt_begin_'+'<?php echo $num; ?>').val(begin);
				$('#dt_end_'+'<?php echo $num; ?>').val(end);

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		});

		$('#nm_def_'+'<?php echo $num; ?>').change(function(event) {
			var def_val = $('#nm_def_'+'<?php echo $num; ?>').val();
			var checked = $('#slc_action_'+'<?php echo $num; ?>').val();
			$('.nm_target').val('');
			if (checked == 1) {
				$('.nm_target_'+'<?php echo $num; ?>').val(def_val);

			} else if (checked == 2) {
				$('.nm_tri_'+'<?php echo $num; ?>').val(def_val);

			} else if (checked == 3) {
				$('.nm_catur_'+'<?php echo $num; ?>').val(def_val);

			} else if (checked == 4) {
				$('.nm_semester_'+'<?php echo $num; ?>').val(def_val);

			} else if (checked == 5) {
				$('.nm_odd_'+'<?php echo $num; ?>').val(def_val);

			} else if (checked == 6) {
				$('.nm_even_'+'<?php echo $num; ?>').val(def_val);

			}
		});



	});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.target-flat_<?php echo $num ?>').hide();
	$('.target-progressive_<?php echo $num ?>').hide();
	$('#slc_target_type_<?php echo $num ?>').change(function(event) {
		$('.target-flat_<?php echo $num ?>').hide();
		$('.target-progressive_<?php echo $num ?>').hide();
		$('#nm_def_<?php echo $num ?>').val('');
		$('#nm_start_<?php echo $num ?>').val('');
		$('#nm_step_<?php echo $num ?>').val('');
		$('.chk_target_<?php echo $num ?>').attr('checked', false);
		$('.nm_target_<?php echo $num ?>').val('');
		$('#slc_action_<?php echo $num ?>').val('');
		if ($(this).val()==1) {
			$('.target-flat_<?php echo $num ?>').show();
		} else if ($(this).val()==2) {
			$('.target-progressive_<?php echo $num ?>').show();
		};
	});

	$('#slc_action_'+'<?php echo $num; ?>').change(function(event) {
		var target_type = $('#slc_target_type_<?php echo $num ?>').val();
		var def_val = $('#nm_def_'+'<?php echo $num; ?>').val();
		var start_val = $('#nm_start_<?php echo $num ?>').val();
		var step_val = $('#nm_step_<?php echo $num ?>').val();
		$('.chk_target_'+'<?php echo $num; ?>').attr('checked', false);
		$('.nm_target_'+'<?php echo $num; ?>').val('');

		if (target_type == 1) {
			if ($(this).val() == 1) {
				$('.chk_target_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_target_'+'<?php echo $num; ?>').val(def_val);

			} else if ($(this).val() == 2) {
				$('.chk_tri_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_tri_'+'<?php echo $num; ?>').val(def_val);

			} else if ($(this).val() == 3) {
				$('.chk_catur_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_catur_'+'<?php echo $num; ?>').val(def_val);

			} else if ($(this).val() == 4) {
				$('.chk_semester_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_semester_'+'<?php echo $num; ?>').val(def_val);

			} else if ($(this).val() == 5) {
				$('.chk_odd_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_odd_'+'<?php echo $num; ?>').val(def_val);

			} else if ($(this).val() == 6) {
				$('.chk_even_'+'<?php echo $num; ?>').attr('checked', true);
				$('.nm_even_'+'<?php echo $num; ?>').val(def_val);

			}

		} else if (target_type == 2) {
			if ($(this).val() == 1) {
				$('.chk_target_<?php echo $num ?>').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					$('#nm_target_<?php echo $num ?>_'+i).val(temp);
					temp = parseFloat(temp) + parseFloat(step_val);

				};
			} else if ($(this).val() == 2) {
				$('.chk_tri_<?php echo $num ?>').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%3==0) {
						$('#nm_target_<?php echo $num ?>_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);

					};

				};

			} else if ($(this).val() == 3) {
				$('.chk_catur_<?php echo $num ?>').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%4==0) {
						$('#nm_target_<?php echo $num ?>_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);

					};

				};

			} else if ($(this).val() == 4) {
				$('.chk_semester_<?php echo $num ?>').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%6==0) {
						$('#nm_target_<?php echo $num ?>_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);

					};

				};

			} else if ($(this).val() == 5) {
				$('.chk_odd_<?php echo $num?>').attr('checked', true);
				var temp = start_val;
				for (var i = 1; i <= 12; i++) {
					if (i%2==1) {
						$('#nm_target_<?php echo $num ?>_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);

					};

				};

			} else if ($(this).val() == 6) {
				$('.chk_even_<?php echo $num ?>').attr('checked', true);
				var temp = start_val;

				for (var i = 1; i <= 12; i++) {
					if (i%2==0) {
						$('#nm_target_<?php echo $num ?>_'+i).val(temp);
						temp = parseFloat(temp) + parseFloat(step_val);

					};

				};

			}
		};


	});
});
</script>
