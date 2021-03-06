<h3>Manage Subordinate</h3>
<ul class="breadcrumb">
	<li><?php echo anchor('manager/subordinate','Subordinate List') ?><span class="divider">/</span></li>
  <li class="active">RKK</li>
</ul>
<?php
	foreach ($PerspectiveList as $row_1) {
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->Perspective.'</strong></div></div>';
		echo '<div class="row">';
		echo '<div class="span12">';
		echo '<table id="table-tree_'.$row_1->PerspectiveID.'" class="table ">';
		echo '<thead><tr><th width="350px">Objective</th><th>Description</th></tr></thead>';
		echo '<tbody>';
		foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
			echo '<tr id="node-'.$row_2->SasaranStrategisID.'_1">';
			echo '<td>'.$row_2->SasaranStrategis.'</td>';
			echo '<td>'.$row_2->Description.'</td>';
			echo '</tr>';

			echo '<tr id="node_'.$row_2->SasaranStrategisID.'_2" class="child-of-node-'.$row_2->SasaranStrategisID.'_1">';
			if(count($KPI_List[$row_2->SasaranStrategisID])>0){
				echo '<td colspan="2">';
				echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'"class="table">';
				echo '<thead><tr><th>KPI</th><th>Count Type</th><th>Unit</th><th>YTD</th><th>Formula</th><th>Bobot</th><th>Target</th><th>Begin</th><th>End</th></tr></thead>';
				echo '<tbody>';
				foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3) {
					echo '<tr id="node-'.$row_3->RKKDetailID.'_1">';
					echo '<td>'.$row_3->KPI.'</td>';
					echo '<td>'.$row_3->CaraHitung.'</td>';
					echo '<td>'.$row_3->Satuan.'</td>';
					echo '<td>'.$row_3->YTD.'</td>';
					echo '<td>'.$row_3->PCFormula.'</td>';
					echo '<td>'.$row_3->Bobot.'</td>';
					echo '<td>'.thousand_separator($row_3->TargetAkhirTahun).'</td>';
					echo '<td>'.substr($row_3->KPI_BeginDate,0,10).'</td>';
					echo '<td>'.substr($row_3->KPI_EndDate,0,10).'</td>';
					echo '<tr/>';
					if(count($Cascade_List[$row_3->RKKDetailID])>0){
						echo '<tr id="node-'.$row_3->RKKDetailID.'_2" class="child-of-node-'.$row_3->RKKDetailID.'_1">';
						echo '<td colspan="9">';
						echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'_'.$row_3->RKKDetailID.'"class="table table-hover">';
						echo '<thead><tr><th>NIK</th><th>KPI</th><th>Description</th><th>Begin</th><th>End</th></tr></thead>';
						echo '<tbody>';
						foreach ($Cascade_List[$row_3->RKKDetailID] as $row_4) {
							echo '<tr>';
							echo '<td>'.$row_4->NIK.'</td>';
							echo '<td>'.$row_4->KPI.'</td>';
							echo '<td>';
							echo '<table class="table table-condensed">';
							echo '<tr><td>Count Type</td><td>'.$row_4->CaraHitung.'</td></tr>';
							echo '<tr><td>Unit</td><td>'.$row_4->Satuan.'</td></tr>';
							echo '<tr><td>YTD</td><td>'.$row_4->YTD.'</td></tr>';
							echo '<tr><td>Formula</td><td>'.$row_4->PCFormula.'</td></tr>';
							echo '<tr><td>Bobot</td><td>'.$row_4->Bobot.'</td></tr>';
							echo '<tr><td>Target</td><td>'.thousand_separator($row_4->TargetAkhirTahun).'</td></tr>';
							echo '</table>';
							echo '</td>';
							echo '<td>'.substr($row_4->KPI_BeginDate,0,10).'</td>';
							echo '<td>'.substr($row_4->KPI_EndDate,0,10).'</td>';

							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</td>';
						echo '</tr>';
					}
				}
				echo '</tbody>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}
		}
		echo'</tbody>';
		echo '</table>';
		echo '</div>';//div from .span12
		echo '</div>';//div from .row
	}

?>