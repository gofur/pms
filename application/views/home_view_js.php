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
		caption: { offset: [0, -40], value: '', position: 'bottom' },
		value: <?php echo $current_score?>, //current
	});

	$('#gaugeBusinsess_2').jqxGauge('value', <?php echo $ytd_score?>); //ytd
	
	
	$('#gaugeBehavior_1').jqxGauge({
		caption: { offset: [0, -40], value: '', position: 'bottom' },
		value: 0,
	});
	
	$('#gaugeBehavior_2').jqxGauge('value', 0);

	$('#gaugeGrandTotal_1').jqxGauge('value', 0);
	
	$('#gaugeGrandTotal_2').jqxGauge('value', 0);
 });
 </script>