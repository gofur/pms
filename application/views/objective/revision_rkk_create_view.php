
<?php
	echo '<div class="row"><div class="span12 header-grid-button">';
	echo anchor($link['create_so'].$RKK->RKKID,'Create SO','class="fancybox btn" data-fancybox-type="iframe"').' ';
	echo anchor($link['finish_rkk'].$RKK->RKKID,'Finish RKK','class="fancybox btn btn-primary"');

	echo '</div></div>';
	foreach ($Perspective_List as $row_1) {
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->Perspective.'</strong></div></div>';
		echo '<div class="row">';
		echo '<div class="span12">';
		echo '<table id="table-tree_'.$row_1->PerspectiveID.'" class="table ">';
		echo '<thead><tr><th width="350px">Objective</th><th>Description</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
		foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
			echo '<tr id="node-'.$row_2->SasaranStrategisID.'_1">';
			echo '<td>'.$row_2->SasaranStrategis.'</td>';
			echo '<td>'.$row_2->Description.'</td>';
			echo '<td>'.anchor($link['edit_so'].$row_2->SasaranStrategisID,'Edit SO','class="fancybox btn" data-fancybox-type="iframe"').' '.anchor($link['create_kpi'].$RKK->RKKID.'/'.$RKK->RKKPositionID.'/'.$row_2->SasaranStrategisID,'Create KPI','class="fancybox btn" data-fancybox-type="iframe"').'</td>';
			echo '</tr>';

			echo '<tr id="node_'.$row_2->SasaranStrategisID.'_2" class="child-of-node-'.$row_2->SasaranStrategisID.'_1">';
			echo '<td colspan="3">';
			echo '<table id="table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'"class="table">';
			echo '<thead><tr><th>KPI</th><th>Count Type</th><th>Unit</th><th>YTD</th><th>Formula</th><th>Bobot</th><th>Target</th><th witdh="80">Action</th></tr></thead>';
			echo '<tbody>';
			foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
				echo '<tr id="node-'.$row_3->RKKDetailID.'_1">';
				echo '<td>'.$row_3->KPI.'</td>';
				echo '<td>'.$row_3->CaraHitung.'</td>';
				echo '<td>'.$row_3->Satuan.'</td>';
				echo '<td>'.$row_3->YTD.'</td>';
				echo '<td>'.$row_3->PCFormula.'</td>';
				echo '<td>'.$row_3->Bobot.'</td>';
				echo '<td>'.anchor($link['edit_target'].$row_3->RKKDetailID,thousand_separator($row_3->TargetAkhirTahun),'class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor($link['edit_kpi'].$row_3->RKKDetailID,'Edit KPI','class="fancybox btn" data-fancybox-type="iframe"') . ' '.
				anchor($link['delimit_kpi'].$row_3->RKKDetailID,'Delimit KPI','class="fancybox btn btn-warning" data-fancybox-type="iframe"') .'</td>';

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
