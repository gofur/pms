<?php echo form_open($action, ''); ?>
<input type="hidden" id="holder_A" name="holder_A">
<div class="row">
	<div class="span2">Action</div>
	<div class="span8">Create</div>
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
			<th>NIK - FullName</th>
			<th>Position</th>
			<th>RKK</th>
			<th>IDP</th>

		</tr>
	</thead>
	<tbody>
		<?php


					echo '<tr>';
					echo '<td>'.$user->NIK.' - '.$user->Fullname.'</td>';
					echo '<td>'.$post.'</td>';
					echo '<td><span class="label">Not Created</span></td>';
					echo '<td><span class="label">Not Created</span></td>';
					echo '</tr>';


		?>
	</tbody>
</table>
	<div class="span12">
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Process</button>
		</div>
	</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
});
</script>