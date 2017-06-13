<?php echo form_open('', ''); ?>
<input type="hidden" id="holder_A" name="holder_A" value="<?php echo $holder_A ;?>">
<div class="row">
	<div class="span2">Action</div>
	<div class="span8">
		<select name="slc_action" id="slc_action" class="input-small">
			<option value=""></option>
			<option value="create">Create</option>
			<option value="assign">Assign</option>
		</select>
	</div>
</div>

<div class="row create-field">
	<div class="span2">RKK & IDP Start</div>
	<div class="span4"><input type="text" name="dt_start"  id="dt_start" value="<?php echo $begin; ?>" class="input-small datepicker"></div>
</div>
<div class="row create-field">
	<div class="span2">RKK & IDP End</div>
	<div class="span4"><input type="text" name="dt_end"  id="dt_end" value="<?php echo $end; ?>" class="input-small datepicker"></div>
</div>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th >NIK - FullName</th>
			<th >Position</th>
			<th width="80">Holding Begin/End </th>
			<th width="80">RKK Begin/End</th>
			<th>KPI Num.</th>
			<th>RKK Weight</th>
			<th>RKK</th>
			<th>IDP</th>
			<th>Status</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			if (isset($sub_ls)) {
				foreach ($sub_ls as $row) {
					echo '<tr>';
					echo '<td>'.$row->NIK.'<br/>'.$row->Fullname.'</td>';
					echo '<td>'.$row->PositionName. '</td>';
					echo '<td>'.substr(isset($row->BeginDate)?$row->BeginDate:'',0,10).'/<br/>'.substr(isset($row->EndDate)?$row->EndDate:'',0,10).'</td>';
					echo '<td>'.substr($sub_data[$row->HolderID]['rkk_start'],0,10) .'/<br>'.substr($sub_data[$row->HolderID]['rkk_end'],0,10).'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['kpi_num'].'</td>';
					echo '<td>'.round($sub_data[$row->HolderID]['rkk_weight'],2).'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['rkk_stat'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['idp_stat'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['stat'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['chk'].'</td>';

					echo '</tr>';
				}
			}
		?>
	</tbody>
</table>
<div class="row">
	<div class="span12">
		<div class="form-actions">
		  <button id="btn_submit" type="submit" class="btn btn-primary">Process</button>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.create-field').hide();
	$('#btn_submit').attr("disabled", true);
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
	$('#slc_action').change(function(event) {
		var base_url = '<?php echo base_url() ?>'+'index.php/';
		$('.create-field').hide();
		$("form").attr("action", "");
		$(".chk_create").attr("disabled", true);
		$('.chk_create').attr('checked', false);

		$(".chk_assign").attr("disabled", true);
		$('.chk_assign').attr('checked', false);
		$('#btn_submit').attr("disabled", true);


		if ($(this).val()=='create') {
			$('.create-field').show();
			$('#btn_submit').removeAttr("disabled");
			$(".chk_create").removeAttr("disabled");
			$('.chk_create').attr('checked', true);

			$("form").attr("action", base_url+"manager/subordinate/add_process");

		} else if ($(this).val()=='assign') {
			$(".chk_assign").removeAttr("disabled");
			$('#btn_submit').removeAttr("disabled");
			
			$('.chk_assign').attr('checked', true);
			$("form").attr("action", base_url+"manager/subordinate/assign_process");

		};
	});
});
</script>