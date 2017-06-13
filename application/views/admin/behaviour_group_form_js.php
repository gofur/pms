<script type="text/javascript">
	$(document).ready(function(){
		$( "#txt_begin_date" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#txt_end_date" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$("#periodForm").validate({
			rules: {
				txt_label:{required:true, maxlength:50},
				txt_begin_date:{required:true,dateISO:true},
				txt_end_date:{required:true,dateISO:true}
			}
		})

		$('form').submit(function() {
		  $(this).find("button[type='submit']").prop('disabled',true);
		});
	})
</script>
