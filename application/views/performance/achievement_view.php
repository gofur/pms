<div class="row titleRKK">
<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i><?php echo $Perspective ?></strong></div></div>
<table class="table">
	<thead>
		<tr><th rowspan="2" width="200">Objective</th><th rowspan="2">KPI</th><th rowspan="2" width="30">Weight</th><th colspan="6" style="color:#4572A7">Current</th><th colspan="5" style="color:#E8601A;">YTD</th></tr>
		<tr>
			<th width="30" style="color:#4572A7">Target</th><th width="40" style="color:#4572A7">Ach.</th><th width="30" style="color:#4572A7">File</th><th width="30" style="color:#4572A7">%</th><th width="30" style="color:#4572A7">PC</th>
			<th width="30" style="color:#E8601A;">Target</th><th width="30" style="color:#E8601A;">Ach.</th><th width="30" style="color:#E8601A;">%</th><th width="30" style="color:#E8601A;">PC</th>
		</tr>
	</thead>
	<tbody><?php
		foreach ($kpi_list as $row) {
			echo '<tr>';
			echo '<td>'.$row->SasaranStrategis.'</td>';
			echo '<td>'.$row->KPI.'</td>';
			echo '<td>'.$row->Bobot.'%</td>';

			//current
			echo '<td>'.anchor($link['history'].$row->RKKDetailID,thousand_separator($monthly_target[$row->RKKDetailID]),'title="History Achivement" class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
			echo '<td>'.thousand_separator($monthly_achv[$row->RKKDetailID]).' '. $input_ach[$row->RKKDetailID].'</td>';

			echo '<td>';
			if ($monthly_evid[$row->RKKDetailID])
			{
				echo '<a title="Download Eviedence" href="http://'.$_SERVER['HTTP_HOST'].$monthly_evid[$row->RKKDetailID].'"><i class="icon-file"></i></a>';
			}
			echo '</td>';
			echo '<td>'.$monthly_persent[$row->RKKDetailID].'</td>';
			echo '<td style="background-color:'.$monthly_color[$row->RKKDetailID].'">'.$monthly_pc_score[$row->RKKDetailID].'</td>';
			//ytd
			echo '<td>'.thousand_separator($ytd_target[$row->RKKDetailID]).'</td>';
			echo '<td>'.thousand_separator($ytd_achv[$row->RKKDetailID]).'</td>';
			echo '<td>'.$ytd_persent[$row->RKKDetailID].'</td>';
			echo '<td style="background-color:'.$ytd_color[$row->RKKDetailID].'">'.$ytd_pc_score[$row->RKKDetailID].'</td>';
			echo '</tr>';
		}
	?></tbody>
</table>	