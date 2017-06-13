<script type="text/javascript">
	$(document).ready(function(){
		<?php 
			if(isset($old->OrganizationID)){
				echo '$("#hiddenDiv1").load("'. base_url().'index.php/admin/user/ajax_position/'. $old->OrganizationID.'/'. $old->PositionID.'");';
			}

		?>
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
				SlcOrg:{required:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true}
			}
		});
		$("#SlcOrg").change(function() {
			$("#hiddenDiv1").load("<?php echo base_url()?>"+'index.php/admin/user/ajax_position/'+$(this).val()+"<?php echo isset($old->PositionID)?'/'.$old->PositionID:''?>");
		});
	})
</script>
