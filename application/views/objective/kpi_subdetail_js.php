<script type="text/javascript">
	$(document).ready(function(){
		$("#section_gen_<?php echo $num_code; ?>").hide();
		$("#slc_generic_<?php echo $num_code; ?>").change(function(){
			if($(this).val()=='other' ){
				$("#section_gen_<?php echo $num_code; ?>").show();
			}else{
				$("#section_gen_<?php echo $num_code; ?>").hide();
			}
		});
	});
</script>	