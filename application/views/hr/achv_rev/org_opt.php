<?php
	echo form_dropdown('slc_org_'.$num, $org_ls, '','data-num="'.  $num .'" class="input-xlarge slc_org" id="slc_org_'.$num.'"');
?>

	<div id="box-org_<?php echo $num+1 ?>"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#slc_org_<?php echo $num?>').change(function(event) {
		if ($(this).val!='') {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			// $("#box-org_<?php echo $num + 1 ?>").empty();
    	// $("#box-emp").remove();
			$.ajax({
        type: "POST",
        url: base_url+'hr/achv_rev/show_child_org',
        data: {
					month  : $('#slc_month').val(),
					parent : $(this).val(),
					num    : $('#slc_org_<?php echo $num ?>').data('num')
				}
      }).done(function(msg) {

        $("#box-org_<?php echo  $num+1 ?>").html(msg);
	    });

	    $.ajax({
	    	url: base_url+'hr/achv_rev/show_post',
	    	type: "POST",
	    	data: {
	    		month : $('#slc_month').val(),
					org   : $('#slc_org_<?php echo $num?>').val()
	    	},
	    })
	    .done(function(msg) {
	    	$("#box-emp").html(msg);
	    })
	    .fail(function() {
	    	console.log("error");
	    })
	    .always(function() {
	    	console.log($('#slc_org_<?php echo $num?>').val());
	    });
	    
		} else {
			$("#box-org_<?php echo $num + 1 ?>").remove();
		};
	});
});
</script>