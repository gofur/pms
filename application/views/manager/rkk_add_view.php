<?php $this->load->view('template/top_1_view'); ?>
<h3>Create RKK & IDP</h3>
<?php $this->load->view('template/header_1_view'); ?>
<hr>
<?php 
	if (isset( $notif_text) && $notif_text!=''){
		$this->load->view('template/notif_1_view');
	}
?>

<?php echo form_open($action_process,'',$hidden) ?>
<div class="row">
	<div class="span2">RKK & IDP Start</div>
	<div class="span4"><input type="text" name="dt_start"  id="dt_start" value="<?php echo $filter_start; ?>" class="input-small"></div>
</div>
<div class="row">
	<div class="span2">RKK & IDP End</div>
	<div class="span4"><input type="text" name="dt_end"  id="dt_end" value="<?php echo $filter_end; ?>" class="input-small"></div>
</div>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>NIK - FullName</th>
			<th>Position</th>
			<th>RKK Begin</th>
			<th>RKK End</th>
			<th>KPI Num.</th>
			<th>RKK Weight</th>
			<th>RKK</th>
			<th>IDP</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			if (isset($sub_ls)) {
				foreach ($sub_ls as $row) {
					echo '<tr>';
					echo '<td>'.$row->NIK.' - '.$row->Fullname.'</td>';
					echo '<td>'.$row->PositionName.'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['rkk_start'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['rkk_end'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['rkk_weight'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['kpi_num'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['rkk_stat'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['idp_stat'].'</td>';
					echo '<td>'.$sub_data[$row->HolderID]['chk'].'</td>';

					echo '</tr>';
				}
			}
		?>
	</tbody>
</table>
<div class="row">
	<div class="span12">
		<input type="submit" value="Create" class="btn btn-primary pull-right">
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( ".datepicker" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#dt_start" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#dt_end" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>