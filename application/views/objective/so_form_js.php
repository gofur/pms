<script type="text/javascript">
	$(document).ready(function(){
		$('#hidden_div_1').load("<?php echo base_url()?>"+'index.php/objective/rkk/ajax_so/');
		$("#txt_SO_num").keyup(function() {
			if($.isNumeric($(this).val()))
			{
				$('#hidden_div_1').load("<?php echo base_url()?>"+'index.php/objective/rkk/ajax_so/'+$(this).val());
			}
		});
		/*$("#genFrom").validate({
			rules: {
				TxtSO:{required:true},
			}
		})*/
	})
</script>