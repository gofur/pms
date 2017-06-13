<div class="row" style="margin-bottom:50px">
	<div class="span6">
		<?php
		$i = 0;
		$max = count($Perspective_list);
		foreach ($Perspective_list as $row) {
			if ($i % 2 == 0)
			{
				echo '<div class="row" style="margin-bottom:20px">';
			}
			echo '<div class="span3">';
			echo '<h5>'.$row->Perspective.'</h5>';
			echo '<div class="gauge-container">';
			echo '<div id="gauge_'.$row->PerspectiveID.'_1" class="gauge_double_1 gauge-double"></div>';
			echo '<div id="gauge_'.$row->PerspectiveID.'_2" class="gauge_double_2 gauge-double"></div>';
			echo '</div></div>';
			if (($max%2==1 && $i==($max-1)) or $i % 2 == 1)
			{
				echo '</div>';
			}
			$i++;
		}
		?>
	</div>
	<div class="span6">
		<center><h2>Total</h2></center>
		<div class="gauge-container">
		<div id="gaugeGrandTotal_1" class="gauge_double_grand_1 gauge-double"></div>
		<div id="gaugeGrandTotal_2" class="gauge_double_grand_2 gauge-double"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="span3">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Range</th>
					<th>Color</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($scale as $row) {
						echo '<tr>';
						echo '<td>'.$row->TPCLow .' - '. $row->TPCHigh .'</td>';
						echo '<td style="background-color:'.$row->Colour.'"></td>';
						echo '</tr>';
					}
				?>

			</tbody>
		</table>
	</div>
	<div class="span9">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Perspective</th>
					<th>Weight</th>
					<th>YTD Weight</th>
					<th>Current TPC</th>
					<th>YTD TPC</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach ($Perspective_list as $row) {
					echo '<tr>';
					echo '<td >'. $row->Perspective.'</td>';
					echo '<td id="p'.$row->PerspectiveID.'_weight">'. '</td>';
					echo '<td id="p'.$row->PerspectiveID.'_ytd_weight">'. '</td>';
					echo '<td id="p'.$row->PerspectiveID.'_cur_tpc"></td>';
					echo '<td id="p'.$row->PerspectiveID.'_ytd_tpc"> </td>';
					echo '</tr>';

				}

				?>
				<tr style="font-weight:bold;">
					<td>Total</td>
					<td id="total_weight"></td>
					<td id="total_ytd_weight"></td>
					<td id="total_cur_tpc"></td>
					<td id="total_ytd_tpc"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="span6">
		<h4>Executive Summary</h4>
		<div class="alert alert-success" id="notif_sum">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  Executive Summary Saved.
		</div>
		<textarea class="span6" rows="5" id="txt_summary"><?php echo $header->Summary ?></textarea>
		<button id="btn_save_sum" class="btn" title="Save Summary">Save</button>
	</div>
	<div class="span6">
		<h4>Notes</h4>
		<div class="alert alert-success" id="notif_notes">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  Notes Saved.
		</div>
		<textarea class="span6" rows="5" id="txt_notes"><?php echo $header->Notes ?></textarea>
		<button id="btn_save_notes" class="btn" title="Save Notes">Save</button>
	</div>
</div>