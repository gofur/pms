
<?php
	foreach ($Perspective_List as $row_1) {
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
			echo '<td colspan="2">';
			echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'"class="table">';
			echo '<thead><tr><th width="300">KPI</th><th>Count Type</th><th>YTD</th><th>Formula</th><th>Weight (%)</th><th>Target</th><th witdh="80">Action</th></tr></thead>';
			echo '<tbody>';
			foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
				echo '<tr id="node-'.$row_3->RKKDetailID.'_1">';
				echo '<td>'.$row_3->KPI.'</td>';
				echo '<td>'.$row_3->CaraHitung.'</td>';
				echo '<td>'.$row_3->YTD.'</td>';
				echo '<td>'.$row_3->PCFormula.'</td>';
				echo '<td>'.$row_3->Bobot.'</td>';
				echo '<td>'.thousand_separator($row_3->TargetAkhirTahun).' '.$row_3->Satuan.'</td>';
				echo '<td>'.anchor($link['cascade_kpi'].$row_3->RKKDetailID,'Cascade','class="fancybox btn pull-right" data-fancybox-type="iframe"').'</td>';
				echo '<tr/>';
				echo '<tr  id="node-'.$row_3->RKKDetailID.'_2" class="child-of-node-'.$row_3->RKKDetailID.'_1">';
				echo '<td colspan="7">';
				echo '<table id="table-tree_'.$row_2->SasaranStrategisID.'_'.$row_3->RKKDetailID.'"class="table">';
				echo '<thead><tr><th width="200">Subordinate</th><th>KPI</th><th width="250">Detail</th><th width="100">Action</th></tr></thead>';
				echo '<tbody>';
				foreach ($Cascade_List[$row_3->RKKDetailID] as $row_4) {
					echo '<tr>';
					echo '<td>'.$row_4->NIK.' - '. $row_4->Fullname.'</td>';
					echo '<td>'.$row_4->KPI.'</td>';
					echo '<td>';
					echo '<table class="table">';
					echo '<tr><td>Count Type</td><td>'.$row_4->CaraHitung.'</td></tr>';
					echo '<tr><td>Formula</td><td>'.$row_4->PCFormula.'</td></tr>';
					echo '<tr><td>YTD</td><td>'.$row_4->YTD.'</td></tr>';
					echo '<tr><td>Weight (%)</td><td>'.$row_4->Bobot.'</td></tr>';
					echo '<tr><td>Target</td><td>'.anchor($link['edit_target'].$row_4->RKKDetailID,thousand_separator($row_4->TargetAkhirTahun).' '.$row_4->Satuan,'class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td></tr>';
						echo '</table>';
						echo '</td>';
					if ($row_4->statusFlag==1 or $row_4->statusFlag==3)
					{
						echo '<td><a href="#" class="btn disabled">Edit KPI</a></td>';
					}
					else
					{
						
						echo '<td>'.anchor($link['edit_kpi'].$row_4->RKKDetailID,'Edit KPI','class="btn fancybox" data-fancybox-type="iframe"' ).'</td>';
					}

					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</td>';
				echo '<tr/>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div></div>';
	}
	
?>
