<h3>Exception Reporting Structure</h3>
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
		echo anchor('admin/exceptionReportingStructure/add/'.$this->session->userdata('isSAP'),'<i class="icon-plus"></i>',' class=" btn pull-right"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table id="example" class="table  table-bordered table-striped table-hover table-condensed">
			<thead><tr><th>Position Chief ID</th><th>Position Sub Ordinate ID</th><th width="150">Position Sub Ordinate Name</th><th width="150">BeginDate</th><th width="150">EndDate</th><th width="150">Action</th></tr></thead>
			<tbody><?php
			foreach ($rowsChiefNonSAP as $row) {
				echo '<tr>';
				echo '<td><strong>'.$row['ChiefPositionID'].' - '.$row['PositionNameChief'].'</strong></td>';
				echo '<td>'.$row['PositionID'].'</td>';
				echo '<td>'.$row['SubOrdinatePositionName'].'</td>';
				echo '<td>'.substr($row['BeginDate'], 0,10).'</td>';
				echo '<td>'.substr($row['EndDate'], 0,10).'</td>';
				echo '<td><div class="btn-group">';
				echo anchor('admin/exceptionReportingStructure/delimit/'.$row['ExceptionReportingStructureID'],'<i class="icon-ban-circle"></i>',''.$notif_type.'title="Delimit" class="btn"');
				echo anchor('admin/exceptionReportingStructure/remove/'.$row['ExceptionReportingStructureID'],'<i class="icon-trash"></i>',''.$notif_type.'title="Remove" class="btn btn-delete"');
				// echo anchor('admin/exceptionReportingStructure/remove/'.$row['ExceptionReportingStructureID'],'<i class="icon-trash"></i>',''.$notif_type.'title="Remove" class="btn"');
				echo '</div></td>';
				echo '</tr>';
			}
			?></tbody>
		</table>
	</div>
</div>
