<script type="text/javascript">
	$(document).ready(function(){
		<?php if(!isset($old->KPIGenericID) or $old->KPIGenericID!=0){
			echo '$("#SectionGen").hide();';
		} ?>
		$("#datepicker").readonlyDatepicker(true);
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
		$("#SlcGeneric").change(function(){
			if($(this).val()=='other' ){
				$("#SectionGen").show();
			}else{
				$("#SectionGen").hide();
			}
		});
		var singleValues = $("#SlcGeneric").val();
		if(singleValues=='other' || singleValues==''){
			$("#genFrom").validate({
				rules: {
					TxtBeginDate:{required:true,dateISO:true},
					TxtEndDate:{required:true,dateISO:true},
					SlcGeneric:{required:true},
					SlcSatuan:{required:true},
					SlcFormula:{required:true},
					SlcYTD:{required:true},
					TxtKPI:{required:true},
					TxtWeight:{required:true,number: true},
					TxtBaseline:{required:true,number: true},
					TxtTarget:{required:true,number: true},

				}
			});
		}else{
			$("#genFrom").validate({
				rules: {
					TxtBeginDate:{required:true,dateISO:true},
					TxtEndDate:{required:true,dateISO:true},
					SlcGeneric:{required:true},
					TxtWeight:{required:true,number: true},
					TxtBaseline:{required:true,number: true},
					TxtTarget:{required:true,number: true},

				}
			});
		}
	})
</script>
