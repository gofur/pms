<table class="table">
	<thead>
		<tr>
			<th width="200">Subordinate</th>
			<th>KPI</th>
			<th width="250">Detail</th>
			<th>Ref</th>
			<th width="60">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($casd_ls as $casd) {
			$key = $casd->isSAP .'|'. $casd->PositionID;
			echo '<tr>';
			echo '<td>'.$casd->NIK.' - '.$casd->Fullname.'<br/>'.$post_ls[$key].'</td>';

			echo '<td>'.$casd->KPI.'</td>';
			echo '<td>';
				echo '<table class="table">';

				echo '<tr><td>Formula</td><td>'.$casd->CaraHitung. '<br/>'.$casd->PCFormula.'</td></tr>';
				echo '<tr><td>YTD</td><td>'.$casd->YTD.'</td></tr>';
				echo '<tr><td>Weight (%)</td><td>'.round($casd->Bobot,2).'</td></tr>';
				echo '<tr><td>Target</td><td>'.$casd->TargetAkhirTahun.'</td></tr>';
				echo '</table>';
			echo '</td>';
			echo '<td>'.'</td>';
			echo '<td>';
				echo '<div class="btn-group btn-group-vertical">';
				echo anchor($link_detail.$casd->KPIID, '<i class="icon-list"></i>', 'title="Detail KPI" class="btn fancybox-nonrefresh" data-fancybox-type="iframe"');
				if (isset($link_relation)) {
					echo anchor($link_relation.$casd->KPIID, '<i class="icon-link"></i>', 'title="Relation KPI" class="btn fancybox-nonrefresh" data-fancybox-type="iframe"');
				}

				if (isset($link_edit)) {
					echo anchor($link_edit.$casd->KPIID, '<i class="icon-pencil"></i>', 'data-kpi="'.$kpi_id_A.'" title="Edit KPI" class="btn fancybox-cascade" data-fancybox-type="iframe"');
				}

				if (isset($link_remove)) {
					echo anchor($link_remove.$casd->KPIID, '<i class="icon-trash"></i>', 'data-kpi="'.$kpi_id_A.'" title="Remove KPI" class="btn fancybox-cascade" data-fancybox-type="iframe"');
					# code...
				}
				echo '</div>';
			echo '</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>