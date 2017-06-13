<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.chk_create').hide();
		$('.chk_assign').hide();
		$('#btn_process').hide();
		$("#dt_start" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$("#dt_end" ).datepicker({
			dateFormat: 'yy/mm/dd',
			changeMonth: true,
			changeYear: true
		});
		$('#slc_action').change(function(event) {
			if ($(this).val() == 0) {
				$('.chk_create').hide();
				$('.chk_assign').hide();
				$('#btn_process').hide();
			} else if ($(this).val() == 1 ) {
				$('.chk_create').show();
				$('.chk_assign').hide();
				$('#btn_process').show();

				 $('form').attr('action', '<?php echo base_url()?>'+'index.php/subordinate/create_process');
			} else if ($(this).val() == 2) {
				$('.chk_create').hide();
				$('.chk_assign').show();
				$('form').attr('action', '<?php echo base_url()?>'+'index.php/subordinate/assign_process');
				$('#btn_process').show();

			}
		});
	});
</script>