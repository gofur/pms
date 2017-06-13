<h3>Formula</h3>
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
		echo anchor('admin/formula/add','<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-striped table-hover">
			<thead><tr><th>Formula</th><th>Counting Type</th><th width="150">Begin</th><th width="150">End</th><th width="10"></th><th width="10"></th><th width="10"></th></tr></thead>
			<tbody><?php
			foreach ($rows as $row) {
				echo '<tr>';
				echo '<td>'.$row->PCFormula.'</td>';
				echo '<td>'.$row->CaraHitung.'</td>';
				echo '<td>'.substr($row->BeginDate, 0,10).'</td>';
				echo '<td>'.substr($row->EndDate, 0,10).'</td>';
				echo '<td>'.anchor('admin/formula/detail/'.$row->PCFormulaID,'<i class="icon-list"></i>','title="Detail"').'</td>';
				echo '<td>'.anchor('admin/formula/edit/'.$row->PCFormulaID,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/formula/delete_process/'.$row->PCFormulaID,'<i class="icon-trash"></i>', array('title="Delete"','onClick' => "return confirm('Are you sure you want to delete?')")).'</td>';
				echo '</tr>';
			}
			?></tbody>
		</table>
	</div>
</div>