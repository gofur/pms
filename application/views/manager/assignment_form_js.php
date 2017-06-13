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
			if(isset($OrganizationID)){
				if(isset($old->AssignmentStatus)!='')
				{
					echo '$("#hiddenDiv1").load("'. base_url().'index.php/manager/assignment/ajax_position/'.$OrganizationID.'/'.$this->session->userdata('isSAP').'/'. $old->PositionID.'/'. $old->AssignmentStatus.'");';
				}
				else
				{
					echo '$("#hiddenDiv1").load("'. base_url().'index.php/manager/assignment/ajax_position/'.$OrganizationID.'/'.$this->session->userdata('isSAP').'/'. $old->PositionID.'/'.$NIKBawahan.'/edit");';
				}
			}
		?>
		$("#additionalAssignFrom").validate({
			rules: {
				SlcOrgID:{selectNone:true},
				SlcPosition:{selectNone:true},
				TxtBobot:{required:true, number:true, min:0, max:100}
			}
		})
		$("#SlcOrgID").change(function() {
			$("#hiddenDiv1").load("<?php echo base_url()?>"+'index.php/manager/assignment/ajax_position/'+$(this).val()+"<?php echo isset($old->PositionID)?'/'.$old->PositionID:'/'.$this->session->userdata('isSAP').'/'?>");
		});
	})


</script>
 