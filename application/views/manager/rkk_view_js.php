<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.treeTable.css" type="text/css" media="screen"/>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.treeTable.js" ></script>

<script type="text/javascript">
	$(document).ready(function(){
		<?php
		foreach ($Perspective_List as $row_1) {
			echo '$("#table-tree_'.$row_1->PerspectiveID.'").treeTable();';
			foreach ($SO_List[$row_1->PerspectiveID] as $row_2){
				echo '$("#table-tree_'.$row_1->PerspectiveID.'_'.$row_2->SasaranStrategisID.'").treeTable();';
			}
		}
		?>
	})
</script>
