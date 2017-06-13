<script type="text/javascript">
	$(document).ready(function(){
		
		$( "#dt_start" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#dt_end" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		// $("#genFrom").validate({
		// 	rules: {
		// 		TxtCaraHitung:{required:true},
		// 		TxtStartDate:{required:true,dateISO:true},
		// 		TxtEndDate:{required:true,dateISO:true}
		// 	}
		// })
	})
</script>