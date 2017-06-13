<script type="text/javascript">
$(document).ready(function() {
	$('.slc_org').hide();
	$('.slc_org_sub').hide();
	$( "#TxtStartDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		$( "#TxtEndDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
});
$(function(){
	$('#slc_org_1').show();
	$.ajax({
		url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_unit",
		data: {org_type: $(this).val(),org_parent:0},
		type: "post",
		success: function(msg){
			$("#slc_org_1").html(msg);
		}

	});

	$('#slc_org_sub_1').show();
	$.ajax({
		url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_unit",
		data: {org_type: $(this).val(),org_parent:0},
		type: "post",
		success: function(msg){
			$("#slc_org_sub_1").html(msg);
		}

	});


	$('.slc_org').change(function(){
		var id_name = $(this).attr('id');
		var id_element = id_name.substr(8,2);
		var next_id = parseInt(id_element) + 1;
		$("#slc_org_sub_"+id_element).val($(this).val());
		if($(this).val()!=''){
			for (var i = next_id ; i <= 7; i++) {
				$("#slc_org_"+i).empty();
				$("#slc_org_"+i).hide();

				$("#slc_org_sub_"+i).empty();
				$("#slc_org_sub_"+i).hide();
			};
			$("#slc_org_"+next_id).show();
			$("#slc_org_sub_"+next_id).show();


			$.ajax({
				url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_sub_unit",
				data: {org_type:$('#slcStatus').val(),org_parent:$(this).val(),element:id_element},
				type: "post",
				success: function(msg){
					$("#slc_org_"+next_id).html(msg);
					$.ajax({
						url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_position",
						data: {org_id: $('#slc_org_'+id_element).val(),org_type:$('#slcStatus').val()},
						type: "post",
						success: function(msg){
							$("#slc_position").html(msg);
						}

					});

				}

			});
			
			$.ajax({
				url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_sub_unit",
				data: {org_type:$('#slcStatus_sub').val(),org_parent:$(this).val(),element:id_element},
				type: "post",
				success: function(msg){

					$("#slc_org_sub_"+next_id).html(msg);

					$.ajax({
						url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_position_sub",
						data: {org_id: $('#slc_org_sub_'+id_element).val(),org_type:$('#slcStatus_sub').val()},
						type: "post",
						success: function(msg){
							$("#post_sub").html(msg);
							var next_id2 = next_id +1;
							$.ajax({
								url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_sub_unit",
								data: {org_type:$('#slcStatus_sub').val(),org_parent:$(this).val(),element:id_element},
								type: "post",
								success: function(msg){

									$("#slc_org_sub_"+next_id2).html(msg);
								}

							});
						}

					});
				}

			});
			
			
		}else{

		}
	});

	$('.slc_org_sub').change(function(){

		var id_name = $(this).attr('id');
		var id_element = id_name.substr(12,2);
		var next_id = parseInt(id_element) + 1;
		if($(this).val()!=''){
			for (var i = next_id ; i <= 7; i++) {
				$("#slc_org_sub_"+i).empty();
				$("#slc_org_sub_"+i).hide();
			};
				$("#slc_org_sub_"+next_id).show();

			$.ajax({
				url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_sub_unit",
				data: {org_type:$('#slcStatus_sub').val(),org_parent:$(this).val(),element:id_element},
				type: "post",
				success: function(msg){

					$("#slc_org_sub_"+next_id).html(msg);
				}

			});
			$.ajax({
				url: "<?php echo base_url() ?>index.php/admin/exceptionReportingStructure/ajax_position_sub",
				data: {org_id: $('#slc_org_sub_'+id_element).val(),org_type:$('#slcStatus_sub').val()},
				type: "post",
				success: function(msg){
					$("#post_sub").html(msg);
				}

			});
		}else{

		}
	});
});
</script>
