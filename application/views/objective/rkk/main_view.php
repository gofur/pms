<?php $this->load->view('template/top_1_view'); ?>
<h3>RKK</h3>
<?php $this->load->view('template/header_2_view'); ?>
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
			var nik = '<?php echo $this->session->userdata("NIK"); ?>';
			$('#spin-subordinate').show();
			$('#spin-rkk').show();

			$.ajax({
        type: "POST",
        url: base_url+'objective/rkk/show_subordinate',
        data: {
								nik 	 : nik,
								holder : $('#SlcPost').val(),
								start  : $('#dt_filter_start').val(),
								end    : $('#dt_filter_end').val()
							},
				error : function(xhr, textStatus, errorThrown ) {
	        if (textStatus == 'timeout') {
	            this.tryCount++;
	            if (this.tryCount <= this.retryLimit) {
	                //try again
	                $.ajax(this);
	                return;
	            }            
	            return;
	        }
	        if (xhr.status == 500) {
	            //handle error
	        } else {
	            //handle error
	        }
    		}
      }).done(function( msg ) {
        $("#box-subordinate").html(msg);
        $('#spin-subordinate').hide();
				$.ajax({
	        type: "POST",
	        url: base_url+'objective/rkk/check_rkk',
	        data: {
									nik 	 : nik,
									holder : $('#SlcPost').val(),
									start  : $('#dt_filter_start').val(),
									end    : $('#dt_filter_end').val()
								},
				 	error : function(xhr, textStatus, errorThrown ) {
		        if (textStatus == 'timeout') {
		            this.tryCount++;
		            if (this.tryCount <= this.retryLimit) {
		                //try again
		                $.ajax(this);
		                return;
		            }            
		            return;
		        }
		        if (xhr.status == 500) {
		            //handle error
		        } else {
		            //handle error
		        }
	    		}
	      }).done(function( msg ) {
	        $('#box-rkk').html(msg);
	        $('#spin-rkk').hide();
	      });
      });


		}
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';
		$(".fancybox-kpi").fancybox({
			closeClick  : false,
			afterClose  : function(){
				var so_id    = $(this.element).data('so');
				
				$('#so-kpi-'+so_id+" td").load(base_url+'objective/rkk/show_kpi',{
					so_id  : so_id, 
					rkk_id : $("#rkk_id").val(),
					begin  : $('#dt_filter_start').val(),
					end    : $('#dt_filter_end').val(),
				} ,
					function(){
					/* Stuff to do after the page is loaded */
				});
      },       
			helpers   : { 
				overlay : {closeClick: false}
			}
		});
	});
</script>

