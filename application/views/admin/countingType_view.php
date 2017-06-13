<h3>Counting Type</h3>
<div class="row">
	<div class="span12">
		<?php 
		if ($notif_text!='' AND $notif_type!='')
		{
			echo '<div class="alert alert-block '.$notif_type.'">';
  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
  		if(isset($notif_title) and $notif_title!=''){
  			echo '<h4>'.$notif_title.'</h4>';
  		}
  		echo $notif_text;
			echo '</div>';
		}
		echo anchor('admin/counting_type/add','<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Tambah"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-striped table-hover">
			<thead><tr><th>Name</th><th width="150">Begin</th><th width="150">End</th><th width="10"></th><th width="10"></th></tr></thead>
			<tbody><?php
			foreach ($rows as $row) {
				echo '<tr>';
				echo '<td>'.$row->CaraHitung.'</td>';
				echo '<td>'.substr($row->BeginDate, 0,10).'</td>';
				echo '<td>'.substr($row->EndDate, 0,10).'</td>';
				echo '<td>'.anchor('admin/counting_type/edit/'.$row->CaraHitungID,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/counting_type/delete_process/'.$row->CaraHitungID, '<i class="icon-trash"></i>', array('title="Delete"','onClick' => "return confirm('Are you sure you want to delete?')")).'</td>';
				echo '</tr>';
			}
			?></tbody>
		</table>
	</div>
</div>
