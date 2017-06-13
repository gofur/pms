<script type="text/javascript">
		$( "#slc_organization" ).change(function(){
			var organization_id = $("#slc_organization").val();
			$.ajax({
				type:"POST",
				<?php if ($do_act=='edit')
				{
				?>
					url:"../get_start_end_date",
				<?php }else{ ?>
					url:"get_start_end_date",
				<?php } ?>
				data:"organization_id="+organization_id,
				cache:false,
				beforeSend: function () {
					$('#state').html('<img src="<?php echo base_url() ?>/img/loader.gif" alt="" width="24" height="24">');
				},
				success: function(html) {
					$("#state").html( html );
				}
			});
		
		});

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

		var aspect_id =0;
		$('#slc_aspect').change(function () {
			var aspect_id = $("#slc_aspect").val();
			if(aspect_id==1)
			{
				$("#slc_behaviour_group").attr("disabled", true);
				$("#slc_layer").attr("disabled", true);
				$("#txt_frequency").attr("readonly", true);
				$("#slc_layer option[value='1']").attr("selected","selected");
				$("#slc_behaviour_group option[value='']").attr("selected","selected");
			}
			else{
				$("#slc_behaviour_group").attr("disabled", false);
				$("#slc_layer").attr("disabled", false);
				$("#txt_frequency").attr("readonly", false);	
			}
		});

		<?php 
		if ($do_act=='edit')
		{
			if($old->aspect_id==1)
			{
				?>
				$("#slc_behaviour_group").attr("disabled", true);
				$("#slc_layer").attr("disabled", true);
				$("#txt_frequency").attr("readonly", true);
		<?php
			}
		}
		?>
	
		$("#periodForm").validate({
			rules: {
				txt_organization_id:{required:true},
				txt_organization_name:{required:true},
				slc_aspect:{required:true},
				txt_percentage:{required:true, number:true},
				txt_begin_date:{required:true,dateISO:true},
				txt_end_date:{required:true,dateISO:true}
			}
		})
		
		$('form').submit(function() {

			var aspect_id = $("#slc_aspect").val();
			if(aspect_id!=1)
			{
				if($('#slc_behaviour_group').val()==''){
 				   	alert('Please, choose an option behaviour group');
    				return false;
				}
				if($('#txt_frequency').val()==''){
 				   	alert('Please, fill frequency');
    				return false;
				}
			}

		  $(this).find("button[type='submit']").prop('disabled',true);
		});
	})
	
</script>
