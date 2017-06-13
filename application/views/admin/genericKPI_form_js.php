<script type="text/javascript">
jQuery.validator.addMethod("selectNone", function(value, element) { 
	if (element.value == "") 
	{ return false; } 
	else return true; 
	}, "Please select an option." ); 

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
		<?php 
			if(isset($CaraHitungID)){
				echo '$("#hiddenDiv1").load("'. base_url().'index.php/admin/genericKpi/ajax_formula/'.$CaraHitungID.'/'. $old->PCFormulaID.'");';
			}

		?>
		$("#genericKpiForm").validate({
			rules: {
				SlcFormula:{selectNone:true},
				SlcCaraHitungID:{selectNone:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true}
			}
		})
		$("#SlcCaraHitungID").change(function() {
			$("#hiddenDiv1").load("<?php echo base_url()?>"+'index.php/admin/genericKpi/ajax_formula/'+$(this).val()+"<?php echo isset($old->PCFormulaID)?'/'.$old->PCFormulaID:''?>");
		});
	})
</script>
