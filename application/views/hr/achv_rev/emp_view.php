<?php $this->load->view('template/top_1_view'); ?>
<h3>Achievement - Subordinate</h3>
<div class="row">
	<div class="span12">

	</div>
</div>
<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span4"><?php echo
 $user_dtl->NIK ;?></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><?php echo
 $user_dtl->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4"><?php echo $post->PositionName;	?>
			</div>
		</div>
		<div class="row">
			<div class="span2">Month</div>
			<div class="span4"><?php 
			$month_ls = array(
				1  => 'Jan', 
				2  => 'Feb', 
				3  => 'Mar', 
				4  => 'Apr', 
				5  => 'May', 
				6  => 'Jun', 
				7  => 'Jul', 
				8  => 'Aug', 
				9  => 'Sep', 
				10 => 'Oct', 
				11 => 'Nov', 
				12 => 'Dec' 
			);
			echo form_dropdown('slc_month', $month_ls,$month,'class="input-small" id="slc_month"');;  
			?></div>
		</div>
		<div class="row">
			<div class="span2"></div>
			<div class="span4"><button class="btn" id="btn_view"><i class="icon-refresh"></i> View</button></div>
		</div>

	</div>
</div>
<i class="icon-spinner icon-spin icon-large icon-4x" id="spin-rkk"></i>
<div id="box-dashboard"></div>
<div id="box-rkk"></div>

<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		refresh_view();
		$(".fancybox-achv").fancybox({
			closeClick  : false,
			afterClose  : function(){
				var so_id    = $(this.element).data('so');
				var base_url = '<?php echo base_url(). "index.php/" ?>';
				var nik  		 = '<?php echo $this->uri->segment(6)?>';
				var post_id  = '<?php echo $this->uri->segment(7)?>';
				var is_sap   = '<?php echo $this->uri->segment(8)?>';
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
									post_id : post_id,
									is_sap  : is_sap,
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
		$('#btn_view').click(function(event) {
			refresh_view();
			
		});

		function refresh_view () {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			var nik  		 = '<?php echo $this->uri->segment(5)?>';
			var post_id  = '<?php echo $this->uri->segment(6)?>';
			var is_sap   = '<?php echo $this->uri->segment(7)?>';


			$.ajax({
        type: "POST",
        url: base_url+'performance/achievement/check_head',
        data: {
								nik 	  : nik,
								post_id : post_id,
								is_sap  : is_sap,
								month   : $('#slc_month').val()
							}
      }).done(function( msg ) {
        $('#box-rkk').html(msg);
        $.ajax({
	        type: "POST",
	        url: base_url+'performance/achievement/show_dashboard',
	        data: {
									nik 	 : nik,
									post_id : post_id,
									is_sap  : is_sap,
									month  : $('#slc_month').val()
								}
	      }).done(function( msg ) {
	      	$('#box-dashboard').html(msg);
	      });

        $('#spin-rkk').hide();

      }).fail(function() {
        $('#spin-rkk').hide();
        // refresh_view();
	       alert('Failed fetch RKK. Please Refresh (press F5 key or click View Button)');
      	// console.log("error RKK");
      });

      
		}
	});
</script>