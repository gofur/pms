<script type="text/javascript">

$(document).ready(function() {
	$('.slc_organization').hide();
	/*$('#slc_organization_3').hide();
	$('#slc_organization_4').hide();
	$('#slc_organization_5').hide();
	$('#slc_organization_6').hide();*/
});

		$( "#slc_organization_1" ).change(function(){
/*
			var id_name = $(this).attr('id');
			var id_element = id_name.substr(8,2);
			var next_id = parseInt(id_element) + 1;
			

			if($(this).val()!=''){
			for (var i = next_id ; i <= 6; i++) {
				$("#slc_organization_"+i).empty();
				$("#slc_organization_"+i).hide();

			};
			
			$.ajax({
				url: "<?php echo base_url() ?>index.php/admin/aspect_setting/ajax_unit",
				data: {org_type: $(this).val(),org_parent:0},
				type: "post",
				success: function(msg){
					$("#slc_organization").html(msg);
				}

			});*/

			var id_name = $(this).attr('id');
			var id_element = id_name.substr(17,2);
			var next_id = parseInt(id_element) + 1;
			//var organization_id = $("#slc_organization_1").val();
			if($(this).val()!=''){
				for (var i = next_id ; i <= 6; i++) {
					$("#slc_organization_"+i).empty();
					$("#slc_organization_"+i).hide();
				};

				$("#slc_organization_"+next_id).show();

				$.ajax({
					url: "<?php echo base_url() ?>index.php/admin/aspect_setting/ajax_sub_unit",
					data: {org_parent:$(this).val(),element:id_element},
					type: "post",
					success: function(msg){
						$("#slc_organization_"+next_id).html(msg);
					}

				});

				
				$.ajax({
					type:"POST",
					url:"get_start_end_date",
					data:"organization_id="+$(this).val(),
					cache:false,
					beforeSend: function () {
						$('#state').html('<img src="<?php echo base_url() ?>/img/loader.gif" alt="" width="24" height="24">');
					},
					success: function(html) {
						$("#state").html( html );
					}
				});
			}
		
		});
	
</script>
