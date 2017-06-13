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
		<?php echo anchor($link_add, '<i class="icon icon-plus"></i>', 'class="btn pull-right" title="Add Project"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Project Name</th>
					<th>Description</th>
					<th>Status</th>
					<th width="150">Action</th>
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
					echo '<td>'.$row->description.'</td>';
					echo '<td>'.$status[$row->is_active].'</td>';
					echo '<td>';
					echo '<div class="btn-group">';
					echo anchor($link_detail.$row->project_id, '<i class="icon-list"></i>', 'class="btn" title="Project Detail"');
					echo anchor($link_edit.$row->project_id, '<i class="icon-pencil"></i>', 'class="btn" title="Edit Project"');
					echo anchor($link_remove.$row->project_id, '<i class="icon-trash"></i>', 'class="btn" title="Remove Project"');
					#action btn
					echo '</div>';
					echo '</td>';

					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "#dt_start" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#dt_end" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>