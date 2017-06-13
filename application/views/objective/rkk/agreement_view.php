<div class="box-agree">
<ul id="myTab" class="nav nav-tabs">
  <li class="active"><a href="#rkk" data-toggle="tab">RKK</a></li>
  <li><a href="#idp" data-toggle="tab">IDP</a></li>
</ul>
<div id="myTabContent" class="tab-content">
  <div class="tab-pane fade in active" id="rkk">
<?php
	$i = 0; 
	foreach ($Perspective_List as $row_1) {
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->Perspective.'</strong></div><div class="span1 pull-right">'.round($per_weight[$i],2).'%</div></div>';
		echo '<div class="row">';
		echo '<div class="span11">';
		echo '<table id="table-tree_'.$row_1->PerspectiveID.'" class="table" style="margin-left:20px">';
		echo '<thead><tr><th width="350px">Objective</th><th>Description</th></tr></thead>';
		echo '<tbody>';
		foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
			if (count($KPI_List[$row_2->SasaranStrategisID])>0)
			{
				echo '<tr id="node-'.$row_2->SasaranStrategisID.'_1">';
				echo '<td>'.$row_2->SasaranStrategis.'</td>';
				echo '<td>'.$row_2->Description.'</td>';
				echo '</tr>';

				echo '<tr id="node_'.$row_2->SasaranStrategisID.'_2" class="child-of-node-'.$row_2->SasaranStrategisID.'_1">';
				echo '<td colspan="2">';
				echo '<table class="table">';
				echo '<thead><tr><th width="300">KPI</th><th>Count Type</th><th>YTD</th><th>Formula</th><th>Weight (%)</th><th>Target</th></tr></thead>';
				echo '<tbody>';
				foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
					echo '<tr >';
					echo '<td>'.$row_3->KPI.'</td>';
					echo '<td>'.$row_3->CaraHitung.'</td>';
					echo '<td>'.$row_3->YTD.'</td>';
					echo '<td>'.$row_3->PCFormula.'</td>';
					echo '<td>'.round($row_3->Bobot,2).'</td>';
					echo '<td>'.anchor($link_detail.$row_3->KPIID,round($row_3->TargetAkhirTahun,2).' '.$row_3->Satuan,'class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';

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
</div>
  <div class="tab-pane fade" id="idp">
<?php
	$i=0;
	foreach ($dev_area_list as $row_1) {
		echo '<div class="row titleRKK">';
		echo '<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i>'.$row_1->DevelopmentAreaType1.' - ';
		if($row_1->DevelopmentAreaType1ID =='1') { 
			echo $nama_kompetensi[$i]; 
		} else { 
			echo $row_1->DevelopmentAreaType; 
		} 
		echo '</strong></div></div>';
		echo '<table class="table">';
		echo '<thead><tr><th width="300">Dev. Program</th><th width="200">Planned Time</th><th width="150">Investment</th><th>Note</th></tr></thead>';
		echo '<tbody>';
		foreach ($training_list[$row_1->IDPDetailID] as $row_2) {
			echo '<tr>';
			echo '<td>'.$row_2->DevelopmentProgram.' - '.$row_2->Description.'</td>';
			echo '<td>'.format_timedate($row_2->Planned_BeginDate) .' - '. format_timedate($row_2->Planned_EndDate).'</td>';
			echo '<td>Rp.'.thousand_separator($row_2->Planned_Investment).'</td>';
			echo '<td>'.$row_2->Notes.'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		$i++;
	}
?>
  </div>
</div>
</div>
<div class="row">
		<?php 
		echo anchor ($link_agree,'Agree','class="btn btn-success span1 offset5"');
		echo anchor ($link_disagree,'Disagree','class="btn btn-danger span1 "');
		?>
</div>