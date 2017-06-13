<?php $this->load->view('template/top_1_view'); ?>
<h3>Project Assignment</h3>
<div class="row">
	<div class="span12">
		<div class="well">
			<?php 
			echo form_open('misc/project', 'class="form-inline"',array());
			echo form_label('Start ', 'dt_start') .' '; 
			echo form_input('dt_start', $start, 'class="input-small" id="dt_start"') .' ';
			echo form_label('End ', 'dt_end') .' '; 

			echo form_input('dt_end', $end, 'class="input-small" id="dt_end"') .' ';
			echo form_submit('btn_search', 'Search','class="btn"');
			echo form_close(); 
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Project Name</th>
					<th>NIK</th>
					<th>Name</th>
					<th>Result</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$status =  array(
					0 => '<span class="label">Inactive</span>',
					1 => '<span class="label label-success">Active</span>',
					);
				foreach ($list as $row) {
					echo '<tr>';
					echo '<td>'.$row->project_name.'</td>';
					echo '<td>'.$row->nik.'</td>';
					echo '<td>'.$row->Fullname.'</td>';
					echo '<td>'.$row->result.'</td>';

					echo '</tr>';
				} ?>
			</tbody>
		</table>
	</div>
</div>