
<table class="table table-hover" >
	<thead>
		<tr>
			<th width="300" rowspan="2">KPI</th>
			<th rowspan="2">Weight (%)</th>
			<th colspan="4" style="color:#4572A7">Current</th>
			<th colspan="4" style="color:#E8601A;">YTD</th>
			<th rowspan="2">Action</th>

		</tr>
		<tr>
			<!-- Current -->
			<th  style="color:#4572A7">Target</th>
			<th  style="color:#4572A7">Achv.</th>

			<th  style="color:#4572A7">%</th>
			<th  style="color:#4572A7">PC</th>
			<!-- YTD -->
			<th  style="color:#E8601A;">Target</th>
			<th  style="color:#E8601A;">Ach.</th>
			<th  style="color:#E8601A;">%</th>
			<th  style="color:#E8601A;">PC</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($kpi_ls as $kpi) {
			if (isset($month_target[$kpi->KPIID]) && $month_target[$kpi->KPIID] != '-') {
				echo '<tr class="success">';
				
			} else {
				echo '<tr>';

			}
			echo '<td>'.$kpi->KPI.'</td>';
			echo '<td>'.round($kpi->Bobot,2).'</td>';
			# CURRENT / MONTHLY
		

			echo '<td>'. $month_target[$kpi->KPIID].'</td>'; // Target Bulanan


			echo '<td>'. $month_achv[$kpi->KPIID];

      # Evidence
			if (isset($month_evid[$kpi->KPIID])) {
				echo ' <a class="att-link" title="Download Eviedence" href="'.base_url().$month_evid[$kpi->KPIID].'"><i class="icon-paperclip icon-large"></i></a>';
			} 
			echo '</td>'; // Achievement & Evidence Bulanan

			echo '<td>'.$month_persen[$kpi->KPIID].'</td>'; // Persentase pemenuhan bulanan

			# Warna PC Score
			if (isset($month_color[$kpi->KPIID])) {
				echo '<td style="background-color:'.$month_color[$kpi->KPIID].'">'; 
				
			} else {
				echo '<td>';

			}
			echo $month_pc[$kpi->KPIID];
			echo '</td>'; // PC Score Bulanan


			# YTD/ Year To Date
			echo '<td>';
			if (isset($ytd_target[$kpi->KPIID])) {
				echo $ytd_target[$kpi->KPIID];
			} else {
				echo '-';
			}
			echo '</td>';

			echo '<td>';
			if (isset($ytd_achv[$kpi->KPIID])) {
				echo $ytd_achv[$kpi->KPIID];
			} else {
				echo '-';
			}
			echo '</td>';

			echo '<td>'.$ytd_persen[$kpi->KPIID].'</td>';
			# Warna PC Score
			if (isset($ytd_color[$kpi->KPIID])) {
				echo '<td style="background-color:'.$ytd_color[$kpi->KPIID].'">'; 
				
			} else {
				echo '<td>';

			}
			echo $ytd_pc[$kpi->KPIID];
			echo '</td>'; // PC Score YTD


			echo '<td>';
				echo '<div class="btn-group btn-group-vertical">';
				echo anchor($link_detail.$kpi->KPIID, '<i class="icon-list"></i>', 'title="Detail KPI" class="btn fancybox-nonrefresh"  data-fancybox-type="iframe"');
				echo anchor($link_history.$kpi->KPIID.'/'.$cur_month, '<i class="icon-tasks"></i>', 'title="History" class="btn fancybox-nonrefresh"  data-fancybox-type="iframe"');

				if (isset($link_input) == TRUE && trim($month_target[$kpi->KPIID]) != '-') {

					echo anchor($link_input.$kpi->KPIID, '<i class="icon-pencil"></i>', 'data-so="'.$kpi->SasaranStrategisID.'" title="Input Achievement" class="btn fancybox-achv"  data-fancybox-type="iframe"');
				}
				echo '</div>';
			echo '</td>';
			echo '</tr>';

		}
		?>
	</tbody>
</table>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
	});
</script>