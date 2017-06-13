<h3>User</h3>
<div class="row">
	<div class="span12">
		<ul class="breadcrumb">
    	<li class="active">List</li>
    </ul>
	</div>
</div>
<div class="row">
	<div class="span12">
		<?php 
		if ($notif_text!='' AND $notif_type!='')
		{
			echo '<div class="alert alert-block alert-success">';
			echo '<a class="close" data-dismiss="alert" href="#">x</a>';
			echo $notif_text;
			echo '</div>';
		}
		echo anchor('admin/user/add','<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add"');?>
	</div>
</div>

<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-stripted table-hover">
			<thead><tr><th>NIK</th><th>Fullname</th><th>Begin</th><th>End</th><th width="10">Action</th></tr></thead>
			<tbody><?php
				foreach ($userList as $row) {
					echo '<tr>';
					echo '<td>'.$row->NIK.'</td>';
					echo '<td>'.$row->Fullname.'</td>';
					echo '<td>'.format_timedate($row->BeginDate).'</td>';
					echo '<td>'.format_timedate($row->EndDate).'</td>';
					echo '<td>'.anchor('admin/user/edit/'.$row->UserID,'<i class="icon-pencil"></i>','class="fancybox" data-fancybox-type="iframe"').' '.anchor('admin/user/detail/'.$row->UserID,'<i class="icon-list"></i>').'</td>';

					echo '</tr>';
				}
			?></tbody>
		</table>
	</div>
</div>