<script type="text/javascript" src="<?php echo base_url();?>js/summernote.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		  $("#txt_description").summernote({
		  	 	height: 100,          
		  	 	width: 500,          
			  	toolbar: [
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['fontsize', ['fontsize']],
			    ['color', ['color']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['height', ['height']],
			    ['codeview',['codeview']]]});

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
				txt_esg:{required:true, maxlength:100},
				txt_begin_date:{required:true,dateISO:true},
				txt_end_date:{required:true,dateISO:true}
			}
		})

		$('form').submit(function() {
		  $(this).find("button[type='submit']").prop('disabled',true);
		});
	})
</script>
