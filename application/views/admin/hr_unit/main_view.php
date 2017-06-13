<?php $this->load->view('template/top_1_view'); ?>
<h3>HR Unit</h3>

<h4>HR Manager</h4>
<div class="row">
	<div class="span12">
	<?php echo anchor('admin/hr_unit/add/man', '<i class="icon-plus"></i>', 'class="btn pull-right"'); ?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIK</th>
					<th>Name</th>
					<th>HR</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach ($hr_man as $row) {
					echo '<tr>';
					echo '<td>'.$row->NIK.'</td>';
					echo '<td>'.$row->Fullname.'</td>';
					echo '<td>'.$row->hr_name.'</td>';
					echo '<td>'.anchor('admin/hr_unit/remove_process/'.$row->NIK, '<i class="icon-trash"></i>', 'class="btn"').'</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<h4>HR Officer</h4>
<div class="row">
	<div class="span12">
	<?php echo anchor('admin/hr_unit/add/off', '<i class="icon-plus"></i>', 'class="btn pull-right"'); ?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIK</th>
					<th>Name</th>
					<th>HR</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach ($hr_off as $row) {
					echo '<tr>';
					echo '<td>'.$row->NIK.'</td>';
					echo '<td>'.$row->Fullname.'</td>';
					echo '<td>'.$row->hr_name.'</td>';
					echo '<td>'.anchor('admin/hr_unit/remove_process/'.$row->NIK, '<i class="icon-trash"></i>', 'class="btn"').'</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('template/bottom_1_view'); ?>