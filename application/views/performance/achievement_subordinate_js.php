<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-tooltip.js" ></script>

<script type="text/javascript">

$(function ()  
{ 
	<?php 
		foreach ($kpi_list as $row) { 
		echo '$("#example_'.$row->KPIID.'").popover();';
		}
	?>
});  
</script>