<script type="text/javascript">
	$(document).ready(function(){
		$("#genFrom").validate({
			rules: {
				SlcFeedbackAspect:{required:true},
				txtFeedbackPoint:{required:true},
				txtEvidence:{required:true},
				txtIssue:{required:true},
				TxtDueDate:{required:true},
				TxtActualDate:{required:true},
				chkList:{required:true},
				txtNotesADP:{required:true},
				txtAltSolution:{required:true}
			}
		})
		$( "#TxtDueDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtActualDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});

	})
</script>
