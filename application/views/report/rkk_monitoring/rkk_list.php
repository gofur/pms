	<div class="row" style="margin-top:10px">
		<div class="span12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th >Total RKK</th>
						<th >Submitted</th>
						<th >Not Yet Submitted</th>
						<th >% Submitted</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $all ;?> </td>
						<td><?php echo $submit ;?> </td>
						<td><?php echo $not_sub ;?> </td>
						<td><?php echo $perc ;?> </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="btn-group pull-right">
				<?php echo anchor($export_xls, '<i class="icon-download"></i>', 'class="btn" title="Export to Excel format"');?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th >NIK</th>
						<th >Name</th>
						<th >Organization</th>
						<th >Position</th>
						<th >KPI Num</th>
						<th >RKK</th>
						<th >IDP</th>
						<th>Status</th>
						
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($temp_list as $row) {
						echo '<tr>';
						echo '<td>'.$row['nik'].'</td>';
						echo '<td>'.$row['fullname'].'</td>';
						echo '<td>'.$row['org_name'].'</td>';
						echo '<td>'.$row['post_name'].'</td>';
						echo '<td>'.$row['kpi_num'].'</td>';
						echo '<td>'.$row['rkk'].'</td>';
						echo '<td>'.$row['idp'].'</td>';
						echo '<td>'.$row['status'].'</td>';
						


						echo '</tr>';
					}
					?>
				</tbody>
			</table>

		</div>
	</div>

