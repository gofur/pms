<h3>User</h3>
<div class="row">
	<div class="span12">
		<ul class="breadcrumb">
    	<li><?php echo anchor('admin/user','List')?> <span class="divider">/</span></li>
    	<li class="active">Detail</li>
    </ul>
	</div>
</div>
<div class="row">
	<div class="span2">NIK</div>
	<div class="span4"><?php echo $head->NIK ?></div>
</div>
<div class="row">
	<div class="span2">Fullname</div>
	<div class="span4"><?php echo $head->Fullname ?></div>
</div>
<div class="row">
	<div class="span2">Email</div>
	<div class="span4"><?php echo $head->Email ?></div>
</div>
<div class="row">
	<div class="span2">Mobile</div>
	<div class="span4"><?php echo $head->Mobile ?></div>
</div>
<div class="row">
	<div class="span2">Role</div>
	<div class="span4"><?php echo $head->Role ?></div>
</div>
<div class="row">
	<div class="span2">Working Status</div>
	<div class="span4"><?php 
		if($head->statusFlag==1){
			echo 'Permanent';
		}else{
			echo 'Contract';
		} ?></div>
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
		echo anchor('admin/user/add_holder/'.$head->NIK,'<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add Holder"');?>
	</div>
</div>

<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-stripted table-hover">
			<thead><tr><th>Organization</th><th>Position</th><th>Main</th><th>Begin</th><th>End</th><th width="10">Action</th></tr></thead>
			<tbody><?php
				foreach ($holderList as $row) {
					echo '<tr>';
					echo '<td>'.$row->OrganizationName.'</td>';
					echo '<td>'.$row->PositionName.'</td>';
					if($row->isMain){
						echo '<td><span class="label label-info">Yes</span></td>';
					}else{
						echo '<td><span class="label">No</span></td>';

					}

					echo '<td>'.format_timedate($row->Holder_BeginDate).'</td>';
					echo '<td>'.format_timedate($row->Holder_EndDate).'</td>';
					echo '<td>'.anchor('admin/user/edit_holder/'.$row->HolderID,'<i class="icon-pencil"></i>','class="fancybox" data-fancybox-type="iframe"').'</td>';
					echo '</tr>';
				}
			?></tbody>
		</table>
	</div>
</div>