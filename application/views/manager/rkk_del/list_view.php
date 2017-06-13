<?php echo form_open('', ''); ?>
<input type="hidden" id="holder_A" name="holder_A" value="<?php echo $holder_A ;?>">
<div class="row">
	<div class="span2">Action</div>
	<div class="span8">
		<select name="slc_action" id="slc_action" class="input-small">
			<option value=""></option>
			<option value="delimit">Delimit</option>
			<option value="remove">Remove</option>
		</select>
	</div>
</div>
<div class="row delimit-field">
	<div class="span2">RKK & IDP End</div>
	<div class="span4"><input type="text" name="dt_end"  id="dt_end" value="<?php echo $end; ?>" class="input-small datepicker"></div>
</div>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th >RKK ID</th>
			<th >NIK - FullName</th>
			<th >Position</th>
			<th>RKK Begin</th>
			<th>RKK End</th>
			<!-- <th>Status</th> -->
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			if (isset($list)) {
				foreach ($list as $row) {
					$key = $row->isSAP .'|'. $row->PositionID;

					echo '<tr>';
					echo '<td>'.$row->RKKID. '</td>';
					echo '<td>'.$row->NIK .' - '. $row->Fullname . '</td>';
					echo '<td>'.$post_name[$key] .'</td>';
					echo '<td>'.substr($row->RKK_BeginDate,0,10).'</td>';
					echo '<td>'.substr($row->RKK_EndDate,0,10).'</td>';
					// echo '<td></td>';
					echo '<td>'. form_checkbox('chk_sub[]', $row->RKKID, FALSE).'</td>';

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
	$('.delimit-field').hide();
	$('#btn_submit').attr("disabled", true);
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
	$('#slc_action').change(function(event) {
		var base_url = '<?php echo base_url() ?>'+'index.php/';
		$('.delimit-field').hide();
		$("form").attr("action", "");
		$(".chk_create").attr("disabled", true);
		$('.chk_create').attr('checked', false);

		$(".chk_assign").attr("disabled", true);
		$('.chk_assign').attr('checked', false);
		$('#btn_submit').attr("disabled", true);


		if ($(this).val()=='delimit') {
			$('.delimit-field').show();
			$('#btn_submit').removeAttr("disabled");
			$(".chk_create").removeAttr("disabled");
			$('.chk_create').attr('checked', true);

			$("form").attr("action", base_url+"manager/rkk_delimit/delimit_process");

		} else if ($(this).val()=='remove') {
			$(".chk_assign").removeAttr("disabled");
			$('#btn_submit').removeAttr("disabled");
			
			$('.chk_assign').attr('checked', true);
			$("form").attr("action", base_url+"manager/rkk_delimit/remove_process");

		};
	});
});
</script>