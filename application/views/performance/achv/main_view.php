<?php $this->load->view('template/top_1_view'); ?>
<h3>Achievement</h3>
<?php $this->load->view('template/header_3_view'); ?>
<i class="icon-spinner icon-spin icon-large icon-4x" id="spin-rkk"></i>
<div id="box-dashboard"></div>

<div id="box-rkk"></div>

<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';

		$(".fancybox-achv").fancybox({
			closeClick  : false,
			afterClose  : function(){
				var so_id    = $(this.element).data('so');
				var base_url = '<?php echo base_url(). "index.php/" ?>';
				var nik      = '<?php echo $this->session->userdata("NIK"); ?>';
				$("#so-kpi-"+so_id).hide();
				$('#so-kpi-'+so_id+" td").load(base_url+'performance/achievement/show_kpi',{
					so_id  : so_id, 
					rkk_id : $("#rkk_id").val(),
					month  : $('#slc_month').val()
				});
				$("#so-kpi-"+so_id).show();
				$.ajax({
	        type: "POST",
	        url: base_url+'performance/achievement/show_dashboard',
	        data: {
									nik 	 : nik,
									holder : $('#SlcPost').val(),
									month  : $('#slc_month').val()
								}
	      }).done(function( msg ) {
	        $('#box-dashboard').html(msg);
					
	      });
        // console.log($(this.element).data('so'));
      },       
			helpers   : { 
				overlay : {closeClick: false}
			}
		});
		
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
			var nik = '<?php echo $this->session->userdata("NIK"); ?>';
			$('#spin-subordinate').show();
			$('#spin-rkk').show();

			$.ajax({
        type: "POST",
        url: base_url+'performance/achievement/show_subordinate',
        data: {
								nik 	 : nik,
								holder : $('#SlcPost').val(),
								month  : $('#slc_month').val()
							}
      }).done(function( msg ) {
        $("#box-subordinate").html(msg);
        $('#spin-subordinate').hide();
	      
	      $.ajax({
	        type: "POST",
	        url: base_url+'performance/achievement/show_dashboard',
	        data: {
									nik 	 : nik,
									holder : $('#SlcPost').val(),
									month  : $('#slc_month').val()
								}
	      }).done(function( msg ) {
	        $('#box-dashboard').html(msg);
					$.ajax({
		        type: "POST",
		        url: base_url+'performance/achievement/check_head',
		        data: {
										nik 	 : nik,
										holder : $('#SlcPost').val(),
										month  : $('#slc_month').val()
									}
		      }).done(function( msg ) {
		        $('#box-rkk').html(msg);
		        $('#spin-rkk').hide();
		      });
					
	      });

	    });



		}
	});
</script>