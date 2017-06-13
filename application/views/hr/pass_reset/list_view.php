<?php $this->load->view('template/top_1_view');?>
<h3>Password Reset</h3>

<?php 
	if (isset($notif)) {
		$this->load->view($notif);
	}
	echo form_open($process, '');
?>

<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Select</th>	
					<th>NIK</th>	
					<th>Name</th>		
				</tr>
			</thead>
			<tbody>
			<?php 
			foreach ($user_ls as $row) {
				echo '<tr>';
				echo '<td>'.form_checkbox('chk_nik[]', $row->NIK, FALSE).'</td>';
				echo '<td>'.$row->NIK.'</td>';
				echo '<td>'.$row->Fullname.'</td>';
				echo '</tr>';
			}
			?>
			</tbody>
		</table>
		
	</div>
</div>
<div class="row">
	<div class="span12">
		<div class="btn-group pull-right">
			<?php echo form_submit('btn_submit', 'Reset','class="btn"'); ?>
			
		</div>
	</div>
</div>
<?php echo form_close();?>

<?php  $this->load->view('template/bottom_1_view');?>
<script>
$(document).ready(function () {
	$(".table").DataTable({

	});
});
</script>