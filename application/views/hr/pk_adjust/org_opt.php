<?php
	echo form_dropdown('slc_org_'.$num, $org_ls, '','data-num="'.  $num .'" class="input-xlarge slc_org" id="slc_org_'.$num.'"');
?>

	<div id="box-org_<?php echo $num+1 ?>"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#slc_org_<?php echo $num?>').change(function(event) {
		if ($(this).val!='') {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			var org_id = $(this).val();
			var scope = $("input[name=rd_scope]:checked").val()
			// $("#box-org_<?php echo $num + 1 ?>").empty();
    	// $("#box-emp").remove();

			$.ajax({
        type: "POST",
        url: base_url+'hr/pk_adjust/show_child_org',
        data: {
					parent : org_id,
					num    : $('#slc_org_<?php echo $num ?>').data('num')
				}
      }).done(function(msg) {
        $("#box-org_<?php echo  $num+1 ?>").html(msg);
        
	    });
	    
		} else {
			$("#box-org_<?php echo $num + 1 ?>").remove();
		};
	});
});
</script>