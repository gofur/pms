<script type="text/javascript">
	$(document).ready(function(){
		$("#genFrom").validate({
			rules: {
				SlcDevArea:{required:true},
				SlcDevProgram:{required:true},
				TxtStartDate:{required:true},
				TxtEndDate:{required:true},
				TxtPlanInvestment:{number:true},
			}
		})
		$( "#TxtBeginDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtEndDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtRealizationBeginDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtRealizationEndDate" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});

		$("#SlcDevArea").change(function() {
			
			if($(this).val()==1)
			{	
				$("#hiddenDivAreaType").load("<?php echo base_url()?>"+'index.php/objective/idp/ajax_devAreaType/'+$(this).val());
				//$("#hiddenDivAreaType").show();
			}
			else if($(this).val()==2)
			{
				//$("#hiddenDivAreaType").hide();
				$("#hiddenDivAreaType").load("<?php echo base_url()?>"+'index.php/objective/idp/ajax_devAreaType/'+$(this).val());
			}
			else
			{
				//$("#hiddenDivAreaType").hide();
				$("#hiddenDivAreaType").load("<?php echo base_url()?>"+'index.php/objective/idp/ajax_devAreaType/'+$(this).val());
			}
		});
	})
</script>
