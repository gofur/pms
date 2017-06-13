<table class="table table-bordered">
	<thead>
		<tr>
			<th>Month</th>
			<th>TPC</th>
			<th>YTD TPC</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$stat_arr = array(
			0 => '<span class="label">Draft</span>' ,
			1 => '<span class="label label-warning">Submitted</span>' ,
			2 => '<span class="label label-error">Rejected</span>' ,
			3 => '<span class="label label-success">Approved</span>',
			4 => '<span class="label label-success">Adjusting</span>',
			5 => '<span class="label label-success">Final</span>',
		);
		foreach ($achv_ls as $row) {
			echo '<tr>';
			echo '<td>'.date('F', mktime(0, 0, 0,$row->Month, 10)).'</td>';
			echo '<td>'.$row->Cur_TPC.'</td>';
			echo '<td>'.$row->YTD_TPC.'</td>';
			echo '<td>'.$stat_arr[$row->Status_Flag].'</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
