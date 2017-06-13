<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-stripted table-hover">
			<thead><tr><th>Periode</th><th>Position</th><th>Start Date</th><th>End Date</th></tr></thead>
			<tbody>
				<?php
				if(isset($list_report))
				{
					foreach ($list_report as $row) {
					echo '<tr><td>'.anchor($link_detail.'pk_report_detail_self/'.$row->nik.'/'.format_timedate($row->Holder_BeginDate).'/'.format_timedate($row->Holder_EndDate).'/'.$row->Periode.'/'.$row->PositionID,''.$row->Periode.'').'</td>';
					echo '<td>'.$row->PositionName.'</td>';
					echo '<td>'.$row->Holder_BeginDate.'</td>';
					echo '<td>'.$row->Holder_EndDate.'</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
