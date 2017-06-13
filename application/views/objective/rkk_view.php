<?php
	$i=0;
	foreach ($Perspective_List as $row_1) {
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->Perspective.'</strong></div><div class="span1 pull-right">'.$per_weight[$i].'%</div></div>';
		echo '<div class="row">';
		echo '<div class="span11">';
		echo '<table id="table-tree_'.$row_1->PerspectiveID.'" class="table" style="margin-left:20px">';
		echo '<thead><tr><th width="350px">Objective</th><th>Description</th></tr></thead>';
		echo '<tbody>';
		foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
			if (count($KPI_List[$row_2->SasaranStrategisID])>0)
			{
				echo '<tr data-tt-id="'.$row_2->SasaranStrategisID.'_1">';
				echo '<td>'.$row_2->SasaranStrategis.'</td>';
				echo '<td>'.$row_2->Description.'</td>';
				echo '</tr>';

				echo '<tr id="node_'.$row_2->SasaranStrategisID.'_2" data-tt-parent-id="'.$row_2->SasaranStrategisID.'_1">';
				echo '<td colspan="2">';
				echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'"class="table">';
				echo '<thead><tr><th width="300">KPI</th><th>Count Type</th><th>YTD</th><th>Formula</th><th>Weight (%)</th><th>Target</th></tr></thead>';
				echo '<tbody>';
				foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
					echo '<tr data-tt-id="'.$row_3->RKKDetailID.'_1">';
					echo '<td>'.anchor('objective/rkk/chief_kpi/'.$row_3->RKKDetailID,$row_3->KPI,'class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
					echo '<td>'.$row_3->CaraHitung.'</td>';
					echo '<td>'.$row_3->YTD.'</td>';
					echo '<td>'.$row_3->PCFormula.'</td>';
					echo '<td>'.$row_3->Bobot.'</td>';
					echo '<td>'.anchor($link['view_target'].$row_3->RKKDetailID,thousand_separator($row_3->TargetAkhirTahun).' '.$row_3->Satuan,'class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';

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
		echo '</div></div>';
		$i++;
	}
?>