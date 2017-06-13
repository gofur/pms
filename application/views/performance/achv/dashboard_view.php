<!-- Dashboard -->
<div class="row">
	<div class="span6">
	<!-- Gauge untuk tiap perspectif -->
	<?php
	$i   = 0;
	$max = count($persp_ls);
	foreach ($persp_ls as $row) {
		if ($i % 2 == 0) {
			echo '<div class="row" style="margin-bottom:20px">';
		}
		echo '<div class="span3">';
		echo '<h5>'.$row->Perspective.'</h5>';
		echo '<div class="gauge-container">';
		echo '<div id="gauge_'.$row->PerspectiveID.'_1" class="gauge_double_1 gauge-double"></div>';
		echo '<div id="gauge_'.$row->PerspectiveID.'_2" class="gauge_double_2 gauge-double"></div>';
		echo '</div></div>';
		if (($max % 2 == 1 && $i == ($max-1) ) OR $i % 2 == 1) {
			echo '</div>';
		}
		$i++;
	}
	?>
	</div>
	<div class="span6">
		<!-- Gauge untuk grand total -->
		<center><h2>Total</h2></center>
		<div class="gauge-container">
		<div id="gaugeGrandTotal_1" class="gauge_double_grand_1 gauge-double"></div>
		<div id="gaugeGrandTotal_2" class="gauge_double_grand_2 gauge-double"></div>
		</div>
	</div>
</div>

<!-- End of Dashboard -->
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
					<th >Current Weight</th>
					<th>YTD Weight</th>
					<th style="color:#4572A7">Current TPC</th>
					<th style="color:#E8601A;">YTD TPC</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach ($persp_ls as $row) {
					echo '<tr>';
					echo '<td >'. $row->Perspective.'</td>';
					echo '<td id="p'.$row->PerspectiveID.'_weight">'.round($persp_weight_cur[$row->PerspectiveID],2). '%</td>';
					echo '<td id="p'.$row->PerspectiveID.'_ytd_weight">'.round($persp_weight_ytd[$row->PerspectiveID],2). '%</td>';
					echo '<td style="color:#4572A7" id="p'.$row->PerspectiveID.'_cur_tpc">'.round($persp_cur_val[$row->PerspectiveID],2).'</td>';
					echo '<td style="color:#E8601A;" id="p'.$row->PerspectiveID.'_ytd_tpc">'.round($persp_ytd_val[$row->PerspectiveID],2).'</td>';
					echo '</tr>';

				}

				?>
				<tr style="font-weight:bold;">
					<td>Total</td>
					<td id="total_weight"><?php echo round($gt_cur_weight,2); ?>%</td>
					<td id="total_ytd_weight"><?php echo round($gt_ytd_weight,2); ?>%</td>
					<td id="total_cur_tpc" style="color:#4572A7"><?php echo round($gt_cur_val,2) ; ?></td>
					<td id="total_ytd_tpc" style="color:#E8601A;"><?php echo round($gt_ytd_val,2) ; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url().'js/jqxcore.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxchart.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxgauge.js'; ?>" ></script>
<script type="text/javascript">
// Setting Bentuk Gauge
$(document).ready(function () {
	$('.gauge_double_1').jqxGauge({
		ranges: [<?php
			$i=1;
			$y=10;
			foreach ($color_range as $row) {
				echo "{ startValue: ".($row->TPCLow*10).", endValue: ".($row->TPCHigh*10).", style: { fill: '$row->Colour', stroke: '$row->Colour' },startDistance: '".$y."%',    endDistance: '".($y-2)."%',startWidth: $i,endWidth: $i+3},";
				$y-=2;
				$i+=3;
			}
		?>],
		min: 0,
		max :<?php echo $max_high *10?>,
		width:220,
		height:220,
		startAngle:30,
		endAngle:270,
		value: 0,
		style: { fill: 'none', stroke: 'none' },
		animationDuration: 1500,
		colorScheme: 'scheme01',
		ticksMinor: { interval: 1, size: '5%' },
		ticksMajor: { interval: 5, size: '10%' },
		border: { style: { fill: 'none', stroke: 'none'}, showGradient: false },
		pointer: { length: '80%', width: '4%' },
		labels: { distance: '50px', position: 'inside', interval: 10, offset: [0, -5], visible: true, formatValue: function (value) {return value/10;}}
		});
	$('.gauge_double_2').jqxGauge({
		min: 0,
		max :<?php echo $max_high *10?>,
		width:220,
		height:220,
		startAngle:30,
		endAngle:270,
		value: 0,
		style: { fill: 'none', stroke: 'none' },
		animationDuration: 800,
		colorScheme: 'scheme03',
		ticksMinor: { visible: false},
		ticksMajor: { visible: false},
		pointer: { length: '65%', width: '3%' },
		border: { style: { fill: 'none', stroke: 'none'}, showGradient: false },
		labels: { visible: false}
	});
	$('.gauge_double_grand_1').jqxGauge({
		ranges: [<?php
			$i=1;
			$y=10;
			foreach ($color_range as $row) {
				echo "{ startValue: ".($row->TPCLow*10).", endValue: ".($row->TPCHigh*10).", style: { fill: '$row->Colour', stroke: '$row->Colour' },startDistance: '".$y."%',    endDistance: '".($y-3)."%',startWidth: $i,endWidth: $i+4},";
				$y-=3;
				$i+=4;
			}
		?>],
		min: 0,
		max :<?php echo $max_high *10?>,
		width:480,
		height:320,
		startAngle:30,
		endAngle:270,
		value: 0,
		caption: { offset: [0, -55], value: '', position: 'bottom' },
		style: { fill: 'none', stroke: 'none' },
		animationDuration: 1500,
		colorScheme: 'scheme01',
		ticksMinor: { interval: 1, size: '5%' },
		ticksMajor: { interval: 5, size: '10%' },
		border: { size: '10%', style: { stroke: '#cccccc'}, visible: false, showGradient: false } ,
		pointer: { length: '80%', width: '4%' },
		labels: { distance: '50px', position: 'inside', interval: 10, offset: [0, -5], visible: true, formatValue: function (value) {return value/10;}}
	});
	$('.gauge_double_grand_2').jqxGauge({
		min: 0,
		max :<?php echo $max_high *10?>,
		width:480,
		height:320,
		startAngle:30,
		endAngle:270,
		value: 0,
		style: { fill: 'none', stroke: 'none' },
		animationDuration: 800,
		colorScheme: 'scheme03',
		ticksMinor: { visible: false},
		ticksMajor: { visible: false},
		pointer: { length: '65%', width: '3%' },
		border: { style: { fill: 'none', stroke: 'none'}, showGradient: false },
		labels: { visible: false}
	});
});
</script>
<script type="text/javascript">
// Nilai Gauge
jQuery(document).ready(function($) {
	<?php
	foreach ($persp_ls as $row) {
	?>
		$('#gauge_<?php echo $row->PerspectiveID; ?>_1').jqxGauge({
			caption : { offset: [0, -40], value: '', position: 'bottom' },
			value : <?php echo $persp_cur_val[$row->PerspectiveID] * 10; ?>
		});
		$('#gauge_<?php echo $row->PerspectiveID; ?>_2').jqxGauge({
			caption : { offset: [0, -40], value: '', position: 'bottom' },
			value : <?php echo $persp_ytd_val[$row->PerspectiveID] * 10; ?>
		});
	<?php
	}	
	?>

	$('#gaugeGrandTotal_1').jqxGauge({
		caption : { offset: [0, -40], value: '', position: 'bottom' },
		value : <?php echo $gt_cur_val * 10; ?>
	});
	$('#gaugeGrandTotal_2').jqxGauge({
		caption : { offset: [0, -40], value: '', position: 'bottom' },
		value : <?php echo $gt_ytd_val * 10; ?>
	});
});
</script>