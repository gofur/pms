<script type="text/javascript" src="<?php echo base_url();?>js/izzyColor.js" ></script>
<script type="text/javascript">
	var imageUrl="<?php echo base_url();?>/img/color.png"; // optionally, you can change path for images.
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
		$("#scaleForm").validate({
			rules: {
				TxtColor:{required:true},
				TxtTPCLow:{number:true,required:true},
				TxtTPCHigh:{number:true,required:true},
				TxtStartDate:{required:true,dateISO:true},
				TxtEndDate:{required:true,dateISO:true},
			}
		})
	})
</script>
