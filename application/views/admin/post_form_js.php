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
		$("#genForm").validate({
			rules: {
				TxtPositionName:{required:true,maxlength:50},
				RdChief:{required:true},
				SlcPositionGroup:{required:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true}
			}
		})
	})
</script>
