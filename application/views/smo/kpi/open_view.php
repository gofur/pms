{persp}
<div class="persp" data-perspid={persp_id} data-org={org_id} data-persplabel="{label}">
	<div class="row titleRKK">
		<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i> {persp_label} </strong></div>
		<div class="span1 pull-right persp_weight" data-perspid={persp_id}>{persp_weight} %</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="btn-group pull-right">
	      <a href="#modal-so" title="Create SO" data-perspid={persp_id} data-org={org_id} data-persplabel="{persp_label}" class="btn btn-so-create" data-toggle="modal"><i class="icon-plus"></i></a>

			</div>
		</div>
	</div>
	<div class="row">
	  <div class="span12">
	    <table class="table">
	      <thead>
	        <tr>
						<th></th>
	          <th>Objective</th>
	          <th>Description</th>
	          <th>Action</th>
	        </tr>
	      </thead>
	      <tbody>
	        {so}
	        <tr data-soid={so_id} >
						<td ><i class="icon-chevron-right icon-large btn btn-link toggle-so"></i></td>
	          <td class="so-label">{so_label}</td>
	          <td class="so-desc">{so_desc}</td>
	          <td>
							<a href="#modal-kpi" data-soid={so_id} title="Create KPI" class="btn btn-kpi-create" data-toggle="modal"><i class="icon-plus"></i></a>
							<a href="#modal-so" title="Edit SO" data-soid={so_id}  class="btn btn-so-edit" data-toggle="modal"><i class="icon-pencil"></i></a>
						</td>
	        </tr>
					<tr class="so-kpi" data-soid={so_id} >
						<td colspan="4">

						</td>
					</tr>
	        {/so}
	      </tbody>
	    </table>
	  </div>
	</div>
</div>
{/persp}

<!-- Modal -->
<div id="modal-so" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Stategic Objective</h3>
  </div>
  <div class="modal-body">
    <form id="form-so" class="form-horizontal">
			<input type="hidden" name="hdn_rkk" id="hdn_rkk" value="{rkk_id}">
			<input type="hidden" name="hdn_org" id="hdn_org" value="{org_id}">
			<input type="hidden" name="hdn_so_soid" id="hdn_so_soid" value="">

      <div class="control-group">
        <label class="control-label">Perspective</label>
        <div class="controls">

          <input type="text" name="txt_persp" id="txt_persp" value="" class="disabled" disabled="disabled">
					<input type="hidden" name="hdn_persp" id="hdn_persp" value="">
          <input type="hidden" name="hdn_so" id="hdn_so" value="">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Strategic Objective</label>
        <div class="controls">
          <input type="text" name="txt_so" id="txt_so" value="" class="input" >
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Description</label>
        <div class="controls">
          <input type="text" name="txt_desc" id="txt_desc" value="" class="input" >
        </div>
      </div>

    </form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button data-dismiss="modal" class="btn btn-primary" id="btn-so-save">Save</button>
  </div>
</div>

<div id="modal-kpi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Key Performance Indicator</h3>
  </div>
  <div class="modal-body">
    <form id="form-kpi" class="form-horizontal">
			<input type="hidden" name="hdn_rkk" id="hdn_rkk" value="{rkk_id}">


      <div class="control-group">
        <label class="control-label">Strategic Objective</label>
        <div class="controls">
          <input type="text" name="txt_kpi_so" id="txt_kpi_so" value="" class="disabled" disabled="disabled">
					<input type="hidden" name="hdn_kpi_so" id="hdn_kpi_so" value="">
          <input type="hidden" name="hdn_kpi_kpiid" id="hdn_kpi_kpiid" value="">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">KPI</label>
        <div class="controls">
          <input type="text" name="txt_kpi" id="txt_kpi" value="" class="input" >
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Description</label>
        <div class="controls">
          <input type="text" name="txt_kpi_desc" id="txt_kpi_desc" value="" class="input" >
        </div>
      </div>
			<div class="control-group">
        <label class="control-label">Unit Satuan</label>
        <div class="controls">
					<select name="slc_unit" id="slc_unit" class="input-medium">
						<option value=""></option>

						{unit}
							<option value="{unitId}">{unitLabel}</option>
						{/unit}
					</select>
        </div>
      </div>
			<div class="control-group">
        <label class="control-label">Formula</label>
        <div class="controls">
					<select name="slc_formula" id="slc_formula" class="input-medium">
						<option value=""></option>

						{formula}
							<option value="{formulaId}">{formulaLabel}</option>
						{/formula}
					</select>
        </div>
      </div>

			<div class="control-group">
        <label class="control-label">YTD</label>
        <div class="controls">
					<select name="slc_ytd" id="slc_ytd" class="input-medium">
						<option value=""></option>

						{ytd}
							<option value="{ytdId}">{ytdLabel}</option>
						{/ytd}
					</select>
        </div>
      </div>
			<div class="control-group">
        <label class="control-label">Weight</label>
        <div class="controls">
          <input type="number" min="0" max="100" name="nm_weight" id="nm_weight" value="" class="input-small" >
        </div>
      </div>
			<h3>Target</h3>
			<div class="control-group">
        <label class="control-label">Pattern Type</label>
        <div class="controls">
					<?php
					$options = array('','Flat','Progressive');
					echo form_dropdown('slc_target_type', $options, '','id="slc_target_type" class="input-medium"');

					?>
        </div>
      </div>

			<div class="control-group target-flat">
				<label class="control-label">Def. Value</label>
				<div class="controls">
					<?php echo form_number('nm_def', '','id="nm_def" step="0.01" class="input-small"');?>
				</div>
			</div>

			<div class="control-group target-progressive">
				<label class="control-label">Start Value</label>
				<div class="controls">
					<?php echo form_number('nm_start', '','id="nm_start" step="0.01" class="input-small"');?>
				</div>
			</div>

			<div class="control-group target-progressive">
				<label class="control-label">Step Value</label>
				<div class="controls">
					<?php echo form_number('nm_step', '','id="nm_step" step="0.01" class="input-small"');?>
				</div>
			</div>

			<div class="control-group target-progressive target-flat">
        <label class="control-label">Repeat</label>
        <div class="controls">
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
			<table class="table table-bordered">
				<tbody>
					<?php
					for ($y=0; $y <= 3 ; $y++) {
						echo '<tr>';
						for ($x=1; $x <4 ; $x++) {
							echo '<th colspan="2">'.date('M',mktime(0,0,0,($x+$y*3),1,2000)).'</th>';
						}
						echo '</tr>';
						echo '<tr>';
						for ($x=1; $x <4 ; $x++) {
							$month_id  = $x+$y*3;
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

							if ($month_id < 1 || $month_id > 12) {
								echo '<td>';
								echo form_checkbox('chk_months[]', $month_id, FALSE,'id="chk_month_'.$month_id.'" class="'.$chk_class.'" disable');
								echo '</td>';

								echo '<td>';
								echo form_number('nm_target_'.$month_id,'','id="nm_target_'.$month_id.'" class="'.$nm_class.'" step="0.01" disable');
								echo '</td>';
							} else {
								echo '<td>';
								echo form_checkbox('chk_months[]', $month_id, '','id="chk_month_'.$month_id.'" class="'.$chk_class.'"');
								echo '</td>';

								echo '<td>';
								echo form_number('nm_target_'.$month_id,'','id="nm_target_'.$month_id.'" class="'.$nm_class.'" step="0.01"');
								echo '</td>';
							}


						}
						echo '</tr>';
					}
					?>
				</tbody>
			</table>

    </form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button data-dismiss="modal" class="btn btn-primary" id="btn-kpi-save">Save</button>
  </div>
</div>
<script>

function recalPerspWeight() {
	var persp = $('.persp_weight');
	var nik = $('#hdn_nik').val();
	var postId = $('#hdn_post_id').val();
	$('.persp_weight').each(function(index) {
		var perspid = $(this).data('perspid');
		$.ajax({
			url: baseUrl+'smo/kpi/weightPersp',
			type: 'POST',
			dataType: 'json',
			data: {nik: nik, postId: postId, perspId: perspid}
		})
		.done(function(respond) {
			$('.persp_weight[data-perspid='+perspid+']').html(respond.weight +' %');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});
}

$('.btn-so-create').click(function(event) {
  /* Act on the event */
	$('#form-so').attr('action', baseUrl+'smo/kpi/createSo');
  $('#txt_persp').val($(this).data('persplabel'));
  $('#hdn_persp').val($(this).data('perspid'));
	$('#txt_so').val('');
  $('#txt_desc').val('');
});

$('.btn-so-edit').click(function(event) {
  /* Act on the event */
	$('#form-so').attr('action', baseUrl+'smo/kpi/editSo');

	$('#hdn_so').val($(this).parent().parent().data('soid'));
	$('#txt_persp').val($(this).parents('div.persp').data('persplabel'));
	$('#hdn_persp').val($(this).parents('div.persp').data('perspid'));

	$('#txt_so').val($(this).parent().parent().children('.so-label').html());
  $('#txt_desc').val($(this).parent().parent().children('.so-desc').html());
});

$('#btn-so-save').click(function(event) {
  /* Act on the event */
	$.ajax({
		url: $('#form-so').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: $('#form-so').serialize(),
	})
	.done(function(respond) {
		console.log("success");
	})
	.fail(function(respond) {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
		showRkk();
	});

});
$(".so-kpi").hide();
$(".toggle-so").toggle(function() {
	$(this).attr('class', 'icon-chevron-down icon-large btn btn-link toggle-so');
	var soId = $(this).parent().parent().data('soid');
	$('.so-kpi[data-soid='+soId+']').show();
	$('.so-kpi[data-soid='+soId+'] td').load(baseUrl+'smo/kpi/showKpi',{
		so_id  : soId,
		rkk_id : $("#hdn_rkk").val()
	})

}, function() {
	$(this).attr('class', 'icon-chevron-right icon-large btn btn-link toggle-so');
	var soId = $(this).parent().parent().data('soid');
	$('.so-kpi[data-soid='+soId+']').hide();
});

$('.btn-kpi-create').click(function(event) {
  /* Act on the event */
	$('#form-kpi').attr('action', baseUrl+'smo/kpi/createKpi');

	resetFormKPI();
	$('#txt_kpi_so').val($(this).parent().parent().children('.so-label').html());
	$('#hdn_kpi_so').val($(this).data('soid'));

});

function resetFormKPI() {
	$('#txt_kpi').val('');
	$('#txt_kpi_desc').val('');
	$('#slc_unit').val('');
	$('#slc_formula').val('');
	$('#slc_ytd').val('');
	$('#nm_weight').val('');
	$('#slc_target_type').val('');
	$('#nm_def').val('');
	$('#nm_start').val('');
	$('#nm_step').val('');
	$('#slc_action').val('');
	$('.chk_target').prop( "checked", false );
	$('.nm_target').val('');
	$('.target-flat').hide();
	$('.target-progressive').hide();
}

$('#btn-kpi-save').click(function(event) {
	var soId = $('#hdn_kpi_so').val();
	$.ajax({
		url: $('#form-kpi').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: $('#form-kpi').serialize(),
	})
	.done(function(respond) {
		console.log("success");
		// showRkk();
		recalTotalWeight();
		recalPerspWeight();
		$('.so-kpi[data-soid='+soId+'] td').load(baseUrl+'smo/kpi/showKpi',{
			so_id  : soId,
			rkk_id : $("#hdn_rkk").val()
		})
	})
	.fail(function(respond) {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});


$('.target-flat').hide();
$('.target-progressive').hide();
$('#slc_target_type').change(function(event) {
	/* Act on the event */
	$('.target-flat').hide();
	$('.target-progressive').hide();

	if ($(this).val() == 1) {
		$('.target-flat').show();

	} else if ($(this).val() == 2) {
		$('.target-progressive').show();

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

</script>
