<?php $this->load->view('template/top_1_view'); ?>
<h3>RKK Revisi - Subordinate</h3>
<?php $this->load->view('template/header_subordinate_view'); ?>
<i class="icon-spinner icon-spin icon-large icon-4x" id="spin-rkk"></i>
<div id="box-rkk">
</div>
<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';
		
		$(".datepicker").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});

		refresh_view();

		$('#btn_view').click(function(event) {
			refresh_view();
			
		});

		function refresh_view () {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			var nik  		 = '<?php echo $this->uri->segment(5)?>';
			var post_id  = '<?php echo $this->uri->segment(6)?>';
			var is_sap   = '<?php echo $this->uri->segment(7)?>';
			$('#spin-subordinate').show();
			$('#spin-rkk').show();

			$.ajax({
        type: "POST",
        url: base_url+'objective/rkkrevisi/show_subordinate',
        data: {
								nik 	  : nik,
								post_id : post_id,
								is_sap  : is_sap,
								start   : $('#dt_filter_start').val(),
								end     : $('#dt_filter_end').val()
							}
      }).done(function( msg ) {
        $("#box-subordinate").html(msg);
        $('#spin-subordinate').hide();
				$.ajax({
	        type: "POST",
	        url: base_url+'objective/rkkrevisi/check_rkk_subordinate',
	        data: {
									nik 	  : nik,
									post_id : post_id,
									is_sap  : is_sap,
									start   : $('#dt_filter_start').val(),
									end     : $('#dt_filter_end').val()
								}
	      }).done(function( msg ) {
	        $('#box-rkk').html(msg);
	        $('#spin-rkk').hide();
	      }).fail(function() {
	        $('#spin-rkk').hide();
	        // refresh_view();
		       alert('Failed fetch RKK. Please Refresh (press F5 key or click View Button)');
	      	// console.log("error RKK");
	      });
      }).fail(function() {
        $('#spin-subordinate').hide();
	       alert('Failed fetch Subordinate. Please Refresh (press F5 key or click View Button)');
	       // refresh_view();
      	// console.log("error Subordinate");
      });

      
		}
	});
</script>