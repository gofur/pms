
<table class="table" style="background-color:#dff0d8;">
	<thead>
		<tr>
			<th width="300">KPI</th>
			<th >Begin<br/>End</th>
			<th>YTD</th>
			<th>Formula</th>
			<th>Weight (%)</th>
			<th>Target</th>
			<th>Ref.</th>
			<th width="60">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($kpi_ls as $kpi) {
			echo '<tr>';
			if ($kpi->KPI_EndDate < date('Y-m-d H:i:s')) {
				echo '<td>'.$kpi->KPI.'</td>';
			
			} else {
				echo '<td><i class="icon-chevron-right icon-large btn btn-link toggle-kpi" data-kpi="'.$kpi->KPIID.'"></i> '.$kpi->KPI.'</td>';
				
			}

			echo '<td>'.substr($kpi->KPI_BeginDate, 0,10).'<br/>'.substr($kpi->KPI_EndDate, 0,10).'</td>';
			echo '<td>'.$kpi->YTD.'</td>';
			echo '<td>'.$kpi->CaraHitung .'<br>'.$kpi->PCFormula.'</td>';
			echo '<td>'.round($kpi->Bobot,2) .'</td>';
			echo '<td>'.$kpi->TargetAkhirTahun.'</td>';
			echo '<td>'.$kpi->Reference.'</td>';
			echo '<td>';
				echo '<div class="btn-group btn-group-vertical">';
				echo anchor($link_detail.$kpi->KPIID, '<i class="icon-list"></i>', 'title="Detail KPI" class="btn fancybox-nonrefresh"  data-fancybox-type="iframe"');
				if (isset($link_edit) == TRUE) {
					echo anchor($link_edit.$kpi->KPIID, '<i class="icon-pencil"></i>', 'data-so="'.$kpi->SasaranStrategisID.'" title="Edit KPI" class="btn fancybox-kpi"  data-fancybox-type="iframe"');
				}

				if (isset($link_rel) == TRUE ){
					echo anchor($link_rel.$kpi->KPIID, '<i class="icon-link"></i>', 'data-kpi="'.$kpi->KPIID.'"title="Maintain KPI Relationship" class="btn fancybox-cascade"  data-fancybox-type="iframe"');
				}
				

				if (isset($link_cascade) == TRUE){
					echo anchor($link_cascade.$kpi->KPIID, '<i class="icon-code-fork icon-flip-vertical icon-large"></i>', 'data-kpi="'.$kpi->KPIID.'"title="Cascade to new KPI" class="btn fancybox-cascade"  data-fancybox-type="iframe"');
				}
				
				if (isset($link_remove) == TRUE) {
					echo anchor($link_remove.$kpi->KPIID, '<i class="icon-trash"></i>', 'data-so="'.$kpi->SasaranStrategisID.'" title="Remove KPI" class="btn fancybox-kpi"  data-fancybox-type="iframe"');
				}
				echo '</div>';
			echo '</td>';
			echo '</tr>';

			echo '<tr id="kpi-cascade-'.$kpi->KPIID.'" class="kpi-cascade">';
			echo '<td colspan="8">';
			echo '</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".kpi-cascade").hide();
		$('.toggle-kpi').toggle(function() {
			$(this).attr('class', 'icon-chevron-down icon-large btn btn-link toggle-kpi');
			var kpi_id = $(this).data('kpi');
			$("#kpi-cascade-"+kpi_id).show();

			var base_url = '<?php echo base_url(). "index.php/" ?>';
			$('#kpi-cascade-'+kpi_id+" td").load(base_url+'objective/rkk/show_cascading',{
				kpi_id  : kpi_id, 
				begin  : $('#dt_filter_start').val(),
				end    : $('#dt_filter_end').val(),
			} ,
				function(){
				/* Stuff to do after the page is loaded */
			})

			/* Stuff to do every *odd* time the element is clicked */
		}, function() {
			$(this).attr('class', 'icon-chevron-right icon-large btn btn-link toggle-kpi');
			var kpi_id = $(this).data('kpi');
			$("#kpi-cascade-"+kpi_id).hide();
			/* Stuff to do every *even* time the element is clicked */
		});
		
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';
		$(".fancybox-kpi").fancybox({
			closeClick  : false,
			afterClose  : function(){
				var so_id    = $(this.element).data('so');
				
				$('#so-kpi-'+so_id+" td").load(base_url+'objective/rkk/show_kpi',{
					so_id  : so_id, 
					rkk_id : $("#rkk_id").val(),
					begin  : $('#dt_filter_start').val(),
					end    : $('#dt_filter_end').val(),
				} ,
					function(){
					/* Stuff to do after the page is loaded */
				});
      },       
			helpers   : { 
				overlay : {closeClick: false}
			}
		});
	});
</script>