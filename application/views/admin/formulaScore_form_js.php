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
		$("#genFrom").validate({
			rules: {
				SlcPCFormulaScore:{required:true},
				TxtPCLow:{required:true,number:true},
				TxtPCHigh:{required:true,number:true},
				TxtPercentage:{number:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true}
			}
		})
	})
</script>
