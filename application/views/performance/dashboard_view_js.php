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
 $(document).ready(function () {
 	
	$('#gaugeBusinsess_1').jqxGauge({
		caption: { offset: [0, -40], value: 'TPC 5', position: 'bottom' },
		value: 35,
	});

	$('#gaugeBusinsess_2').jqxGauge('value', 37.5);
	
	<?php
		foreach ($Perspective_list as $row) {
	?>	
			$('#gauge_<?php echo $row->PerspectiveID; ?>_1').jqxGauge({
				caption: { offset: [0, -40], value: '', position: 'bottom' },
				value: <?php 
				if ($perspective_weight[$row->PerspectiveID]!=0)
				{
					echo ($perspective_current[$row->PerspectiveID]/$perspective_weight[$row->PerspectiveID]) * 1000; 
				}
				else
				{
					echo 0;
				}
				?>,
			});
			$('#gauge_<?php echo $row->PerspectiveID; ?>_2').jqxGauge('value', <?php 
				if ($perspective_weight[$row->PerspectiveID]!=0)
				{
					echo ($perspective_ytd[$row->PerspectiveID]/$perspective_weight[$row->PerspectiveID]) * 1000; 
				}
				else
				{
					echo 0;
				}
				?>);
	<?php
		}

	?>

	$('#gaugeGrandTotal_1').jqxGauge('value', <?php echo $current_value*10; ?>);
	
	$('#gaugeGrandTotal_2').jqxGauge('value',  <?php echo $ytd_value*10; ?>);
 });
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		//transfer nilai ke table
		<?php
			$total_ytd_weight = 0;
			$total_weight = 0;
			foreach ($Perspective_list as $row) {
				$total_ytd_weight += $perspective_weight[$row->PerspectiveID];
				$total_weight     += $perspective_weight_1[$row->PerspectiveID];
				echo '$("#p'.$row->PerspectiveID.'_weight").html("'.$perspective_weight_1[$row->PerspectiveID] .'%");';
				echo '$("#p'.$row->PerspectiveID.'_ytd_weight").html("'.$perspective_weight[$row->PerspectiveID] .'%");';
				if ($perspective_weight[$row->PerspectiveID] != 0) {
					echo '$("#p'.$row->PerspectiveID.'_cur_tpc").html('.round(($perspective_current[$row->PerspectiveID]/$perspective_weight[$row->PerspectiveID]) * 100,2) .');';
					echo '$("#p'.$row->PerspectiveID.'_ytd_tpc").html('.round(($perspective_ytd[$row->PerspectiveID]/$perspective_weight[$row->PerspectiveID]) * 100,2) .');';

				}else {
					echo '$("#p'.$row->PerspectiveID.'_cur_tpc").html("-");';

					echo '$("#p'.$row->PerspectiveID.'_ytd_tpc").html("-");';
				}

				
			}
		?>
		$("#total_weight").html("<?php echo $total_weight ?> %");

		$("#total_ytd_weight").html("<?php echo $total_ytd_weight ?> %");

		$("#total_cur_tpc").html((<?php echo $current_value; ?>).toFixed(2));
		$("#total_ytd_tpc").html((<?php echo $ytd_value; ?>).toFixed(2));
		
		$("#notif_sum").hide();
		$("#notif_notes").hide();

		// button save for summary
		$('#btn_save_sum').click(function(event) {
			$("#btn_save_sum").html('Saving...');
			$('#btn_save_sum').attr("disabled", "disabled");
			$.ajax({
				url: '<?php echo base_url() ?>'+'index.php/performance/achievement/save_summary',
				type: 'POST',
				data: {acvh_id: <?php echo $header->RKKAchievementID ?>,summary: $('#txt_summary').val()},
			})
			.done(function() {

				$("#notif_sum").show();
			  $("#btn_save_sum").html('Saved');

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			  $("#btn_save_sum").html('Save');
				$('#btn_save_sum').removeAttr("disabled", "disabled")

			});
		});

		// button save for notes
		$('#btn_save_notes').click(function(event) {
			$("#btn_save_notes").html('Saving...');
			$('#btn_save_notes').attr("disabled", "disabled");
			$.ajax({
				url: '<?php echo base_url() ?>'+'index.php/performance/achievement/save_notes',
				type: 'POST',
				data: {acvh_id: <?php echo $header->RKKAchievementID ?>,notes: $('#txt_notes').val()},
			})
			.done(function() {

				$("#notif_notes").show();
				
			  $("#btn_save_notes").html('Saved');

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			  $("#btn_save_notes").html('Save');
				$('#btn_save_notes').removeAttr("disabled", "disabled")

			});
		});
	});
</script>