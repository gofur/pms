
<div class="row">
	<div class="span12">
    <ul class="breadcrumb">
		  <li><?php echo anchor ($link['view_self'],'Chief')?><span class="divider">/</span></li>
		  <li class="active"><?php echo $Bawahan->NIK .' - '. $Bawahan->Fullname  ?></li>
    </ul>
	</div>
</div>

<?php
	foreach ($Perspective as $row_1) {
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
			echo '<thead><tr><th width="250">KPI</th><th>Count Type</th><th>YTD</th><th>Formula</th><th>Weight (%)</th><th>Target</th></tr></thead>';
			echo '<tbody>';
			foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
				echo '<tr id="node-'.$row_3->RKKDetailID.'_1">';
				echo '<td><a href="#" rel="popover" id="example_'.$row_3->KPIID.'" data-content="'.$row_3->Description.'" data-placement="right" data-trigger="hover" data-original-title="KPI Description"> '.$row_3->KPI.' </a></td>';
				echo '<td>'.$row_3->CaraHitung.'</td>';
				echo '<td>'.$row_3->YTD.'</td>';
				echo '<td>'.$row_3->PCFormula.'</td>';
				echo '<td>'.$row_3->Bobot.'</td>';
				echo '<td>'.anchor($link['view_target'].$row_3->RKKDetailID,thousand_separator($row_3->TargetAkhirTahun).' '.$row_3->Satuan,'class="fancybox-nonrefresh" data-fancybox-type="iframe"' ).'</td>';
				//echo '<td>'.thousand_separator($row_3->TargetAkhirTahun).' '.$row_3->Satuan.'</td>';
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