
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
						
						<?php 
			
							$flag =0 ;
							foreach ($aspect_ls as $row) {
								if ($flag < 2) {
								 	echo '<th>'.$row->label.' ('.$row->percent.'%)</th>';
								 	$flag++;
								}
							} 
							echo '<th>Project</th>';
							echo '<th>Total</th>';
							echo '<th>After Adjustment</th>';
							echo '<th>Category</th>';
							echo '<th>Notes</th>';
						
						?>
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
						for ($i=0; $i < $sub_period; $i++) { 
							$flag = 0 ;
							foreach ($aspect_ls as $row_2) {
								if ($flag < 2) {
									if (isset($row[$row_2->aspect_id])) {
										echo '<td>'.$row[$row_2->aspect_id].'</td>';
										# code...
									} else {
										echo '<td>-</td>';
									}
									$flag++;
								}
							}
							echo '<td>'.$row['project'].'</td>';
							echo '<td>'.$row['total'].'</td>';
							echo '<td>';
							echo $row['adjustment'];
							echo '</td>';
							echo '<td style="background-color:'.$row['color'].'">'.$row['category'].'</td>';
							echo '<td>'.anchor($row['notes_link'], '<i class="icon icon-file-text"></i>', 'class="fancybox-nonrefresh"  data-fancybox-type="iframe"').'</td>';
						}


						echo '</tr>';
					}
					?>
				</tbody>
			</table>

		</div>
	</div>

