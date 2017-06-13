<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-tooltip.js" ></script>

<script type="text/javascript">

$(function ()  
{ 
	<?php 
		foreach ($Perspective as $row_1) { 
			foreach ($SO_List[$row_1->PerspectiveID] as $row_2)
			{
				foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3){
					echo '$("#example_'.$row_3->KPIID.'").popover();';		
				}
			}
		
		}
	?>
});  
</script>