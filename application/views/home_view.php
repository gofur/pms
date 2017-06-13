<?php $this->load->view('template/top_1_view'); ?>
<div class="row" style="margin-bottom:50px;">
	<div class="span3">
		<h5>Business Aspect</h5>
		<center><b>70%</b></center>
		<div class="gauge-container">
		<div id="gaugeBusinsess_1" class="gauge_double_1 gauge-double"></div>
		<div id="gaugeBusinsess_2" class="gauge_double_2 gauge-double"></div>
		</div>				
	</div>
	<div class="span3">
		<h5>Behaviour Aspect</h5>
		<center><b><?=$percentage?>%</b></center>
		<div class="gauge-container">
		<div id="gaugeBehavior_1" class="gauge_double_1 gauge-double"></div>
		<div id="gaugeBehavior_2" class="gauge_double_2 gauge-double"></div>
		</div>

	</div>
	<div class="span2">
		<h5>Project Assignment</h5>
		<div style="font-size:48px;margin-top:100px;text-align:center"><?php echo $proj_result; ?></div>
	</div>
	<div class="span4">
		<h4>Grand Total</h4>
		<div class="gauge-container">
		<div id="gaugeGrandTotal_1" class="gauge_double_grand_1 gauge-double"></div>
		<div id="gaugeGrandTotal_2" class="gauge_double_grand_2 gauge-double"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<th>Aspect</th>
				<th>Weight</th>
				<th>Result</th>
				<th >Subtotal</th>
			</thead>
			<tbody>
				<tr>
					<td>Business Aspect</td>
					<td>70%</td>
					<td><?php echo round($ba_ytd/10,2) ?></td>
					<td style="text-align:right"><?php echo round($ba_ytd/10*0.7,2) ?></td>
				</tr>
				<tr>
					<td>Behavior Aspect</td>
					<td><?=$percentage?>%</td>
					<td><?php echo round($be_ytd/10,2) ?></td>
					<td style="text-align:right"><?php echo round($be_ytd/10*0.3,2) ?></td>
				</tr>
				<tr>
					<td>Project</td>
					
					
					<td colspan="3" style="text-align:right"><?php echo round($proj_result,2) ?></td>
				</tr>
				
				<tr>
					<td>Total</td>
					<td>100%</td>
					<td colspan="2" style="text-align:right"><?php echo round($gt_ytd/10,2)?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="span12">
		<h3>Business Aspect</h3>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>As Position</th>
					<th>Begin</th>
					<th>End</th>
					<th>Last YTD TPC</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($rkk_ls as $row) {
					echo '<tr>';
					echo '<td><i class="icon-chevron-right icon-large btn btn-link toggle-achv" data-rkk="'.$row->RKKID.'"></i> '.$post_name[$row->RKKID].'</td>';
					echo '<td>'.substr($row->BeginDate,0,10).'</td>';
					echo '<td>'.substr($row->EndDate,0,10).'</td>';
					echo '<td>'.$achv_ls[$row->RKKID].'</td>';
					echo '</tr>';
					echo '<tr class="achv-rkk" id="achv-rkk-'.$row->RKKID.'" data-rkk="'.$row->RKKID.'">';
					echo '<td colspan=4">';

					echo '</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="span12">
		<?php echo $notif_ls ?>
	</div>
</div>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript" src="<?php echo base_url().'js/jqxcore.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxchart.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxgauge.js'; ?>" ></script>
<script type="text/javascript">
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
		width:280,
		height:220,
		startAngle:30,
		endAngle:270,
		value: 0,
		caption: { offset: [0, -40], value: '<?php echo round($gt_ytd/10,2) ?>', position: 'bottom', size:'150%' },
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
		width:280,
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
});	
</script>
<script type="text/javascript">
 $(document).ready(function () {
 	
	$('#gaugeBusinsess_1').jqxGauge({
		caption: { offset: [0, -35], value: '<?php echo round($ba_ytd/10,2) ?>', position: 'bottom' },
		value: <?php echo $ba_cur ?>, //current
	});

	$('#gaugeBusinsess_2').jqxGauge('value', <?php echo $ba_ytd?>); //ytd
	
	
	$('#gaugeBehavior_1').jqxGauge({
		caption: { offset: [0, -35], value: '<?php echo round($be_ytd/10,2) ?>', position: 'bottom' },
		value: <?php echo $be_ytd?>,
	});
	
	$('#gaugeBehavior_2').jqxGauge('value', <?php echo $be_ytd?>);

	$('#gaugeGrandTotal_1').jqxGauge('value', <?php echo $gt_cur ;?>);
	
	$('#gaugeGrandTotal_2').jqxGauge('value', <?php echo $gt_ytd ;?>);
 });
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".achv-rkk").hide();
		$(".toggle-achv").toggle(function() {
			$(this).attr('class', 'icon-chevron-down icon-large btn btn-link toggle-achv');
			var rkk_id = $(this).data('rkk');
			$("#achv-rkk-"+rkk_id).show();

			var base_url = '<?php echo base_url(). "index.php/" ?>';
			$('#achv-rkk-'+rkk_id+" td").load(base_url+'home/show_monthly_achv',{
				rkk_id : rkk_id
			} ,
				function(){
				/* Stuff to do after the page is loaded */
			});
			

		}, function() {
			$(this).attr('class', 'icon-chevron-right icon-large btn btn-link toggle-so');
			var rkk_id = $(this).data('rkk')
			$("#achv-rkk-"+rkk_id).hide();
		});

		
	});
</script>
