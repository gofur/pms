<?php $this->load->view('template/top_1_view'); ?>
<h3>Detail Project</h3>
<div class="row">
	<div class="span3">Project Title</div>
	<div class="span9"><?php echo $project->project_name;?></div>
</div>
<div class="row">
	<div class="span3">Document Num</div>
	<div class="span9"><?php echo $project->doc_num;?></div>
</div>
<div class="row">
	<div class="span3">Description</div>
	<div class="span9"><?php echo $project->description;?></div>
</div>
<div class="row">
	<div class="span3">Scope</div>
	<div class="span9"><?php 
	$scope = array(0 => 'Corporate', 1 => 'Unit');
	echo $scope[$project->scope] ;

	?></div>
</div>
<div class="row">
	<div class="span3">Begin</div>
	<div class="span9"><?php echo substr($project->begin_date, 0,10);?></div>
</div>
<div class="row">
	<div class="span3">End</div>
	<div class="span9"><?php echo substr($project->end_date, 0,10);?></div>
</div>

<div class="row">
	<div class="span3">Status</div>
	<div class="span9"><?php 
	$status = array(0 => 'Inactive', 1 => 'Active');
	echo $status[$project->is_active] ;

	?></div>
</div>
<div class="row">
	<div class="span12">
		<div class="btn-group pull-right">
			<?php echo anchor($link_add, '<i class="icon-plus"></i>', 'title="Add Member" class="btn"'); ?>
		</div>
	</div>
</div>
<table class="table datatable table-hover">
	<thead>
		<tr>
			<th>NIK - Fullname</th>
			<th>Position</th>
			<th>Result</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		foreach ($member_ls as $row) {
			echo '<tr>';
			echo '<td>'.$row->nik. ' - '. $name_ls[$row->member_id].'</td>';
			if ($row->is_chief) {
				echo '<td>'.$row->role_name.' <i class="icon-star"></i> </td>';
			} else {
				echo '<td>'.$row->role_name.'</td>';

			}
			echo '<td>'.round($row->result,2).'</td>';
			$status =  array(
				0 => '<span class="label">Inactive</span>',
				1 => '<span class="label label-success">Active</span>',
			);
			echo '<td>'.$status[$row->is_active].'</td>';
			echo '<td>';
			echo '<div class="btn-group">';
			echo anchor($link_edit.$row->member_id, '<i class="icon-pencil"></i>', 'class="btn" title="Edit Member"');
			echo anchor($link_remove.$row->member_id, '<i class="icon-trash"></i>', 'class="btn" title="Remove Member"');
			echo '</div>';
			echo '</td>';
			echo '</tr>';
		}
	?>
	</tbody>
</table>
<?php $this->load->view('template/bottom_1_view'); ?>
