<?php
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->Perspective.'</strong></div>';
		echo anchor($link['create_so'].'/'.$row_1->PerspectiveID,'<i class="icon-plus"></i>','class="fancybox btn pull-right" data-fancybox-type="iframe" title="Create SO" ');
		echo '</div>';
		echo '<div class="row">';
		echo '<div class="span12">';
		echo '<table id="table-tree_'.$row_1->PerspectiveID.'" class="table ">';
		echo '<thead><tr><th width="350px">Objective</th><th>Description</th><th width="100">Action</th></tr></thead>';
		echo '<tbody>';
		if (isset($SO_List[$row_1->PerspectiveID]) && count($SO_List[$row_1->PerspectiveID])){
			foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
				echo '<tr id="node-'.$row_2->SasaranStrategisID.'_1">';
				echo '<td>'.$row_2->SasaranStrategis.'</td>';
				echo '<td>'.$row_2->Description.'</td>';
				echo '<td>';
				echo anchor($link['create_kpi'].$row_2->SasaranStrategisID,'<i class="icon-plus"></i>','title="Create KPI" class="btn fancybox" data-fancybox-type="iframe"');			
				echo '</td>';
				echo '</tr>';

				echo '<tr id="node_'.$row_2->SasaranStrategisID.'_2" class="child-of-node-'.$row_2->SasaranStrategisID.'_1">';
				echo '<td colspan="3">';
				echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'"class="table">';
				echo '<thead><tr><th width="250">KPI</th><th>Count Type</th><th>YTD</th><th>Formula</th><th>Weight (%)</th><th>Target</th><th witdh="100">Action</th></tr></thead>';
				echo '<tbody>';
				if (isset($KPI_List[$row_2->SasaranStrategisID]) && count($KPI_List[$row_2->SasaranStrategisID])){
					foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
						echo '<tr id="node-'.$row_3->RKKDetailID.'_1">';
						echo '<td>'.$row_3->KPI.'</td>';
						echo '<td>'.$row_3->CaraHitung.'</td>';
						echo '<td>'.$row_3->YTD.'</td>';
						echo '<td>'.$row_3->PCFormula.'</td>';
						echo '<td>'.$row_3->Bobot.'</td>';
						echo '<td>'.anchor($link['edit_target'].$row_3->RKKDetailID,thousand_separator($row_3->TargetAkhirTahun).' '.$row_3->Satuan,'class="fancybox-nonrefresh" data-fancybox-type="iframe"' ).'</td>';
						echo '<td>'.anchor($link['edit_kpi'].$row_3->RKKDetailID,'<i class="icon-pencil"></i>','class="btn fancybox" data-fancybox-type="iframe"');
						echo ' '.anchor($link['link_kpi'].$row_3->RKKDetailID,'<i class="icon-magnet"></i>','class="fancybox btn" data-fancybox-type="iframe"');
						echo ' '.anchor($link['delimit_kpi'].$row_3->RKKDetailID,'<i class="icon-trash icon-white"></i>','class="fancybox btn btn-warning" data-fancybox-type="iframe"');
						echo'</td>';
						echo '<tr/>';
					}
				}
				echo '</tbody>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div></div>';

	
?>