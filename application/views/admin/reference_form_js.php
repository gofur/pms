<script type="text/javascript">
	$(document).ready(function(){
		$( "#TxtStartDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtEndDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$("#referenceForm").validate({
			rules: {
				TxtReference:{required:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true}
			}
		})
	})
</script>
