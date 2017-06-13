<?php $this->load->view('template/top_1_view'); ?>
<h3>Achievement Revision</h3>
<?php $this->load->view('template/header_4_view'); ?>
<h4>Employee</h4>
<div class="row">
	<div class="span4" id="box-org">

	</div>

	<div class="span8" id="box-emp"  style="height:354px;overflow:scroll;overflow-x:hidden;">

	</div>

</div>

<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';

		refresh_view();

		$('#btn_view').click(function(event) {
			refresh_view();
			
		});

		function refresh_view () {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			var nik = '<?php echo $this->session->userdata("NIK"); ?>';
			$('#spin-root-org').show();
			$('#spin-rkk').show();

			$.ajax({
        type: "POST",
        url: base_url+'hr/achv_rev/show_root_org',
        data: {
								month  : $('#slc_month').val()
							}
      }).done(function( msg ) {
        $("#box-org").html(msg);
				$('#spin-root-org').hide();


	    });



		}
	});
</script>