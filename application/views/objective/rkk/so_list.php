<div class="row titleRKK">
	<div class="span10 titleRKKDetail"><strong><i class="icon-chevron-right"></i> <?php echo $persp->Perspective ?> </strong></div> 
	<div class="span1 pull-right"><?php echo round($weight,2) ?> %</div>
</div>
<div class="row">
	<div class="span12">
		<div class="btn-group pull-right">
			<?php 
			if (isset($link_create)) {
				echo anchor($link_create.$persp->PerspectiveID, '<i class="icon-plus"></i>', 'title="Create SO" class="btn fancybox"  data-fancybox-type="iframe"');
			}
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table ">
			<thead>
				<tr>
					<th width="350px">Objective</th>
					<th>Description</th>

					<th width="120">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($so_ls as $so) {
						echo '<tr data-so="'.$so->SasaranStrategisID.'" id="so-row-'.$so->SasaranStrategisID.'" class="so-row">';
						echo '<td><i class="icon-chevron-right icon-large btn btn-link toggle-so" data-so="'.$so->SasaranStrategisID.'"></i> '.$so->SasaranStrategis.'</td>';
						echo '<td>'.$so->Description.'</td>';
						echo '<td>';
							echo '<div class="btn-group">';

							if (isset($link_edit)) {
								echo anchor($link_edit.$so->SasaranStrategisID, '<i class="icon-pencil"></i>', 'title="Edit SO" class="btn fancybox"  data-fancybox-type="iframe"');
							}
							if (isset($link_remove)) {
								echo anchor($link_remove.$so->SasaranStrategisID, '<i class="icon-trash"></i>', 'title="Remove SO" class="btn fancybox"  data-fancybox-type="iframe"');
							}

							if (isset($link_create_kpi)) {
								echo anchor($link_create_kpi.$so->SasaranStrategisID, '<i class="icon-plus"></i>', 'title="Create KPI" class="btn fancybox-kpi"  data-fancybox-type="iframe"');
							}
							echo '</div>';
						echo '</td>';

						echo '</tr>';

						echo '<tr class="so-kpi" data-soID="'.$so->SasaranStrategisID.'" id="so-kpi-'.$so->SasaranStrategisID.'">';
						echo '<td colspan="3" >';
						echo '</td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
	</div>
</div>

