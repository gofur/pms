<script type="text/javascript">
	$(document).ready(function(){
		$("#genFrom").validate({
			rules: {
				SlcDevArea:{required:true},
				SlcDevProgram:{required:true},
				TxtStartDate:{required:true},
				TxtEndDate:{required:true},
				TxtPlanInvestment:{number:true}
			}
		})
		<?php if(isset($add_realization)==NULL)
		{
		?>
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
		<?php }else{ ?>

			$( "#txt_begindate_realization" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
			});
			$( "#txt_enddate_realization" ).datepicker({
				dateFormat: 'yy/mm/dd',
				changeMonth: true,
				changeYear: true
			});
		<?php } ?>

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
