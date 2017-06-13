<?php $this->load->view('template/top_1_view'); ?>
<h3>Delimit/Remove RKK & IDP</h3>
<?php $this->load->view('template/header_1_view'); ?>
<hr>
<?php 
	if (isset( $notif_text) && $notif_text!=''){
		$this->load->view('template/notif_1_1view');
	}
?>

<?php echo form_open($action,'',$hidden); ?>
<div class="row">
	<div class="span2">End Date</div>
	<div class="span4"><input type="text" name="dt_end"  id="dt_end" value="<?php echo substr($period->EndDate, 0,10); ?>" class="input-small"></div>
</div>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>RKK ID</th>
				<th>NIK - Fullname</th>
				<th>Position</th>
				<th>RKK Begin</th>
				<th>RKK End</th>
				<th colspan="2">Status</th>

			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($rows as $row) {
				$key           = $row->isSAP .'|'. $row->PositionID;
				echo '<tr>';
				echo '<td>'.$row->RKKID. '</td>';
				echo '<td>'.$row->NIK .' - '. $row->Fullname . '</td>';
				echo '<td>'.$post_name[$key] .'</td>';
				echo '<td>'.substr($row->RKK_BeginDate,0,10).'</td>';
				echo '<td>'.substr($row->RKK_EndDate,0,10).'</td>';
				if ($period->EndDate == $row->RKK_EndDate) {
					$status = '<span class="label label-success">Active</span>';
				} else {
					$status = '<span class="label ">Inactive</span>';
				}
				echo '<td>'.$status.'</td>';
				echo '<td>'. form_checkbox('chk_sub[]', $row->RKKID, FALSE).'</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
<div class="row">
	<div class="span12">
		<div class="form-actions">
		  <button type="submit" class="btn btn-warning">Delimit</button>
		</div>
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
		$( "#dt_end" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>